<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentClassController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\ClocksController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [LoginController::class, 'store']);
Route::get('/logout', [LogoutController::class, 'store'])->middleware(['auth:sanctum']);
Route::get('/me', [MeController::class, 'index'])->middleware(['auth:sanctum']);

Route::group([
    'prefix' => '/password',
], function () {
    Route::post('/', [PasswordController::class, 'store']);
    Route::put('/', [PasswordController::class, 'update']);
    Route::post('/validate-reset-token', [PasswordController::class, 'validateResetToken']);
});

Route::get('get-user-to-register/{user:invite_token}', [RegisterController::class, 'userToRegister']);
Route::post('register/{user:invite_token}', [RegisterController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('docenten', UserController::class);
    Route::post('/docenten/{docenten}/reinvite', [UserController::class,'reinvite']);

    Route::resource('students', StudentController::class);
    Route::post('/students/{student}/minutes', [StudentController::class, 'addOpenMinutesToStudent']);

    Route::resource('class', StudentClassController::class)->parameters([
        'class' => 'studentClass'
    ]);

    Route::get('/class/get/lastCreated', [StudentClassController::class, 'latestCreated']);
    Route::post('/class/{studentClass}/students', [StudentClassController::class, 'importStudents']);

    // Code system
    Route::post('/code/login', [CodeController::class, 'submitCode']);
    Route::get('/code/get', [CodeController::class, 'getCode']);
    Route::post('/code/regenerate', [CodeController::class, 'generateNewCode']);

    // Public clock system
    Route::resource('/clocks', ClocksController::class);
    Route::post('/clock/in', [ClocksController::class, 'clockIn']);
    Route::post('/clock/out', [ClocksController::class, 'clockOut']);
    Route::post('/clocks/change-presence', [ClocksController::class, 'changePresence']);
});
