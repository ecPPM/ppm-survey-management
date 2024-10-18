<?php

use App\Http\Controllers\EnumeratorController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

Route::middleware(['auth', 'role:admin'])->get('/surveys', [SurveyController::class, 'index'])->name('surveys');

Route::middleware(['auth', 'role:admin'])->prefix('surveys/{id}')->group(function () {
    Route::get('/', [SurveyController::class, 'show'])->name('surveys.show');
    Route::get('/settings', [SurveyController::class, 'settings'])->name('surveys.settings');
    Route::get('/enumerators', [SurveyController::class, 'enumerators'])->name('surveys.enumerators');
    Route::get('/enumerators/{enum_id}/respondents', [SurveyController::class, 'respondents'])->name('surveys.respondents');
    Route::get('/dashboard', [SurveyController::class, 'dashboard'])->name('surveys.dashboard');
    Route::get('/questionnaire', [SurveyController::class, 'questionnaire'])->name('surveys.questionnaire');
    Route::post('/upload-enumerator-list', [SurveyController::class, 'upload'])->name('upload.enumerator');
    Route::get('/download-enumerator-template', [SurveyController::class, 'downloadEnumeratorTemplate'])->name('download.enumerator.template');
});

Route::middleware(['auth', 'role:admin'])->get('/users', [UserManagementController::class, 'index'])->name('users');

Route::middleware(['auth', 'role:enumerator'])->get('/info', [EnumeratorController::class, 'info'])->name('info');
Route::middleware(['auth', 'role:enumerator', 'mode:Register,Both'])->get('/registration', [EnumeratorController::class, 'registration'])->name('registration');
Route::middleware(['auth', 'role:enumerator', 'mode:Deploy,Both'])->get('/deployment', [EnumeratorController::class, 'deployment'])->name('deployment');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'role:admin'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
