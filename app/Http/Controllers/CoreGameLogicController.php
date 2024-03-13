<?php

namespace App\Http\Controllers;

use App\Enums\GameStatus;
use App\Http\Requests\GuessNumberRequest;
use App\Models\GameSession;
use Illuminate\Http\JsonResponse;
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
        }

        if ($oneEightDigitsExist) {
            $indexOfOne = array_search(1, $secretDigits);
            $indexOfEight = array_search(8, $secretDigits);
            $this->moveDigit($secretDigits, $indexOfOne, $indexOfEight);
        }

        $this->moveDigit4($secretDigits, $fourDigitExist);
        $this->moveDigit5($secretDigits, $fiveDigitExist, $fourDigitExist);
    }

    private function moveDigit5(array &$secretDigits, bool $fiveDigitExist, bool $fourDigitExist): void
    {
        if ($fiveDigitExist) {
            $toIndex = 3;
            $indexOfFive = array_search(5, $secretDigits);
            if ($indexOfFive % 2 == 0) {
                $this->moveDigit($secretDigits, $indexOfFive, $toIndex);
                $this->moveDigit4($secretDigits, $fourDigitExist);
            }
        }
    }

    private function moveDigit4(array &$secretDigits, bool $fourDigitExist): void
    {
        if ($fourDigitExist) {
            $toIndex = 1;
            $indexOfFour = array_search(4, $secretDigits);
            if ($indexOfFour % 2 == 0) {
                $this->moveDigit($secretDigits, $indexOfFour, $toIndex);
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

    public function guessSecretNumber(GuessNumberRequest $request): JsonResponse
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
           return $this->winGame();
        }

        return response()->json(['result' => $result]);
    }

    public function quitGame(): JsonResponse
    {
        $this->updateGameSessionStatus(GameStatus::SURRENDERED);
        session()->forget(['secretNumber', 'attempts']);

        return response()->json(["failureGameMessage" => "GAME OVER! [Wait to reload ...]"]);
    }

    private function winGame(): JsonResponse
    {
        $attempts = session('attempts');
        $secretNumber = implode(session('secretNumber'));

        $this->updateGameSessionStatus(GameStatus::WON, $attempts);
        session()->forget(['secretNumber', 'attempts']);

        return response()->json(
            ["successGameMessage" => "You guessed the number $secretNumber with $attempts attempts ! [Wait to reload ...]"]
        );
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
