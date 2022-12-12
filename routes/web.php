<?php

use App\Http\Controllers\CoreGameLogicController;
use App\Http\Controllers\GameSessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('/dashboard', [GameSessionController::class, 'index'])->name('dashboard');
Route::post('gameSession/store', [GameSessionController::class, 'store']);

Route::post('/secretNumber/{guessNumber}/guess', [CoreGameLogicController::class, 'guessSecretNumber']);
Route::post('/quitGame', [CoreGameLogicController::class, 'quitGame']);
