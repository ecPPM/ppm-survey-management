<?php

use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->get('/surveys', [SurveyController::class, 'index'])->name('surveys');

Route::middleware(['auth', 'verified'])->prefix('surveys/{id}')->group(function () {
    Route::get('/', [SurveyController::class, 'show'])->name('surveys.show');
    Route::get('/settings', [SurveyController::class, 'settings'])->name('surveys.settings');
    Route::get('/respondents', [SurveyController::class, 'respondents'])->name('surveys.respondents');
    Route::get('/questionnaire', [SurveyController::class, 'questionnaire'])->name('surveys.questionnaire');
});


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
