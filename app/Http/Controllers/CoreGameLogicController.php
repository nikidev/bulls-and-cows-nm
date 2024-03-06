<?php

namespace App\Http\Controllers;

use App\Enums\GameStatus;
use App\Http\Requests\GuessNumberRequest;
use App\Models\GameSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CoreGameLogicController extends Controller
{
    private function generateSecretNumber(): array
    {
        $advancedRules = true;
        $secretDigits = [];

        for ($i = 0; $i <= 3; $i++) {
            $digit = rand(0, 9);
            $secretDigits[] = $digit;
        }

        if ($this->checkForDuplicateDigits($secretDigits)) {
            return $this->generateSecretNumber();
        }

        if ($advancedRules) {
            $this->includeAdvancedRules($secretDigits);
        }

        return $secretDigits;
    }

    private function includeAdvancedRules(array &$secretDigits): void
    {
        $oneEightDigitsExist = in_array(1, $secretDigits) && in_array(8, $secretDigits);
        $fourDigitExist = in_array(4, $secretDigits);
        $fiveDigitExist = in_array(5, $secretDigits);

        if ($oneEightDigitsExist && $fourDigitExist && $fiveDigitExist) {
            $this->generateSecretNumber();
            return;
        }

        sort($secretDigits);

        if ($oneEightDigitsExist) {
            $indexOfOne = array_search(1, $secretDigits);
            $indexOfEight = array_search(8, $secretDigits);
            $this->moveDigit($secretDigits, $indexOfOne, $indexOfEight);
        }

        if ($fourDigitExist) {
            $indexOfFour = array_search(4, $secretDigits);
            if ($indexOfFour % 2 == 0) {
                $this->moveDigit($secretDigits, $indexOfFour, 1);
            }
        }

        if ($fiveDigitExist) {
            $indexOfFive = array_search(5, $secretDigits);
            if ($indexOfFive % 2 == 0) {
                $this->moveDigit($secretDigits, $indexOfFive, 3);
            }
        }
    }

    private function moveDigit(array &$secretDigits, int $fromIndex, int $toIndex): void
    {
        $replacement = array_splice($secretDigits, $fromIndex, 1);
        array_splice($secretDigits, $toIndex, 0, $replacement);
    }

    private function compareNumbers(array $secretNumber, array $guessNumber, int &$attempts): array
    {
        $bulls = 0;
        $cows = 0;

        for ($i = 0; $i <= 3; $i++) {
            if ($guessNumber[$i] == $secretNumber[$i]) {
                $bulls++;
            }
            elseif (in_array($guessNumber[$i], $secretNumber)) {
                $cows++;
            }
        }

        $attempts++;

        session(['attempts' => $attempts]);

        return ['bulls' => $bulls, 'cows'=> $cows];
    }

    private function checkForDuplicateDigits($digits): bool
    {
        return count($digits) > count(array_unique($digits));
    }

    public function guessSecretNumber(GuessNumberRequest $request): RedirectResponse
    {
        if (!session()->has('secretNumber')) {
            $secretNumber = $this->generateSecretNumber();
            session(['secretNumber' => $secretNumber]);
        }

        $secretNumber = session('secretNumber');

        $guessNumber = $request->input('guessNumber');

        $guessNumber = array_map('intval', str_split($guessNumber));

        $attempts = session('attempts', 0);

        $result = $this->compareNumbers($secretNumber, $guessNumber, $attempts);
        $result['guessNumber'] = implode($guessNumber);

        if ($result['bulls'] == 4) {
            $this->winGame();
        }

        return redirect()->back()->with('result', $result);
    }

    public function quitGame(): RedirectResponse
    {
        $this->updateGameSessionStatus(GameStatus::SURRENDERED);
        session()->forget(['secretNumber', 'attempts']);

        return redirect()->back()->with('failureGameMessage', 'GAME OVER!');
    }

    private function winGame(): void
    {
        $attempts = session('attempts');

        $this->updateGameSessionStatus(GameStatus::WON, $attempts);
        session()->forget(['secretNumber', 'attempts']);

        redirect()->back()->with('successGameMessage', "You guessed the number with $attempts attempts !");
    }

    private function updateGameSessionStatus(GameStatus $gameStatus, int $attempts = null): void
    {
        $gameSession = GameSession::where('user_id', Auth::user()->id)
                    ->latest('id')
                    ->first();

        $gameSession->update([
            'guess_attempts' => $attempts,
            'is_won' => $gameStatus
        ]);
    }
}
