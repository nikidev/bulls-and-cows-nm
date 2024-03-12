<?php

namespace App\Http\Controllers;

use App\Enums\GameStatus;
use App\Models\GameSession;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GameSessionController extends Controller
{
    public function index()
    {
        $top10ByHighScoreUsers = User::select(
            'users.id',
            'users.name',
            DB::raw('SUM(gs.is_won) as won_games')
        )
        ->join('game_sessions as gs', 'users.id', '=', 'gs.user_id')
        ->where('gs.is_won', '=', GameStatus::WON)
        ->groupBy('users.id')
        ->orderByDesc('won_games')
        ->take(10)
        ->get();

        return view('dashboard', ['top10ByHighScoreUsers' => $top10ByHighScoreUsers]);
    }

    public function store(): JsonResponse
    {
        GameSession::create([
            'user_id' => Auth::user()->id,
        ]);

        return response()->json(['message' => 'The game started!']);
    }
}
