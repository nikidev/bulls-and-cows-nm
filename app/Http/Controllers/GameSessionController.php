<?php

namespace App\Http\Controllers;

use App\Enums\GameStatus;
use App\Models\GameSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameSessionController extends Controller
{
    public function index() {
        $top10ByAttempts = DB::table('game_sessions as gs')
                        ->selectRaw('u.name, guess_attempts, TIMEDIFF(gs.updated_at, gs.created_at) as time')
                        ->join('users as u', 'gs.user_id', '=', 'u.id')
                        ->where('is_won','=', GameStatus::WON)
                        ->orderBy('guess_attempts', 'ASC')
                        ->orderByRaw('(gs.updated_at - gs.created_at) ASC')
                        ->limit(10)
                        ->get();

        $top10ByTime = GameSession::selectRaw('users.name, guess_attempts, TIMEDIFF(game_sessions.updated_at, game_sessions.created_at) as time')
                    ->join('users', 'game_sessions.user_id', '=', 'users.id')
                    ->where('is_won','=', GameStatus::WON)
                    ->orderByRaw('(game_sessions.updated_at - game_sessions.created_at) ASC')
                    ->orderBy('guess_attempts', 'ASC')
                    ->limit(10)
                    ->get();


        return view('dashboard', ['top10ByAttempts' => $top10ByAttempts, 'top10ByTime' => $top10ByTime]);
    }
}
