<?php

use App\Http\Controllers\Auth\AcceptanceOfApplicationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post('/registration', [RegisteredUserController::class, 'store'])
    ->name('registration');
    
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('login');

Route::post('/accept-application/{application}', [AcceptanceOfApplicationController::class, 'store'])
    ->middleware(['auth:sanctum', 'teacher'])
    ->name('accept.store');

Route::delete('/accept-application/{application}', [AcceptanceOfApplicationController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'teacher'])
    ->name('accept.destroy');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ->name('logout');

Route::get('/applications', [ApplicationController::class, 'index'])
    ->middleware(['auth:sanctum', 'admin']);

Route::get('/users/tests', [UserController::class, 'index'])
    ->middleware(['auth:sanctum', 'teacher']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json($request->user()->only(['id', 'name', 'email', 'role']));
});

Route::get('/user/tests', [UserController::class, 'show'])
    ->middleware('auth:sanctum');

Route::delete('/user/{id}', [UserController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'teacher']);

Route::get('/tests', [TestController::class, 'index']);

Route::post('/tests', [TestController::class, 'store'])
    ->middleware(['auth:sanctum', 'teacher']);

Route::get('/tests/{test}', [TestController::class, 'show']);

Route::get('/tests/{test}/edit', [TestController::class, 'edit'])
    ->middleware(['auth:sanctum', 'teacher']);

Route::get('/tests/{test}/questions', [QuestionController::class, 'index'])
    ->middleware('auth:sanctum');

Route::post('/tests/{test}/questions', [QuestionController::class, 'store'])
    ->middleware('auth:sanctum');

Route::patch('/tests/{test}', [TestController::class, 'update'])
    ->middleware(['auth:sanctum', 'teacher']);

Route::delete('/tests/{test}', [TestController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'teacher']);
