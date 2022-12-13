<?php

namespace App\Http\Controllers;

use App\Enums\GameStatus;
use App\Models\GameSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoreGameLogicController extends Controller
{
    public function __construct(
        protected Request $request
    ){}

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

    private function includeAdvancedRules(&$secretDigits): void
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

    private function moveDigit(&$secretDigits, $fromIndex, $toIndex)
    {
        $replacement = array_splice($secretDigits, $fromIndex, 1);
        array_splice($secretDigits, $toIndex, 0, $replacement);
    }

    private function compareNumbers($secretNumber, $guessNumber, $attempts)
    {
        $bulls = 0;
        $cows = 0;

        if ($this->checkForDuplicateDigits($guessNumber)) {
            return;
        }

        for ($i = 0; $i <= 3; $i++) {
            if ($guessNumber[$i] == $secretNumber[$i]) {
                $bulls++;
            }
            elseif (in_array($guessNumber[$i], $secretNumber)) {
                $cows++;
            }
        }

        $attempts++;

        if ($bulls == 4) {
            $this->winGame($attempts);
        }

        return $this->compareNumbers($secretNumber, $guessNumber, $attempts);
    }

    private function printGreetingsMessage($attempts): string
    {
        echo "You won with $attempts attempts!";
        return "You won with $attempts attempts!";
    }

    private function checkForDuplicateDigits($digits): bool
    {
        return count($digits) > count(array_unique($digits));
    }

    public function guessSecretNumber($guessNumber)
    {
        $secretNumber = $this->generateSecretNumber();
        $guessNumber = array_map('intval', str_split($guessNumber));
        $attempts = 0;

        $this->compareNumbers($secretNumber, $guessNumber, $attempts);
    }

    public function quitGame(): void
    {
        $this->updateGameSessionStatus(GameStatus::SURRENDERED);
    }

    private function winGame($attempts): void
    {
        $this->updateGameSessionStatus(GameStatus::WON, $attempts);
    }

    private function updateGameSessionStatus($gameStatus, $attempts = null): void
    {
        GameSession::where('user_id', Auth::user()->id)->latest('user_id')->update([
            'guess_attempts' => $attempts,
            'is_won' => $gameStatus
        ]);
    }
}
