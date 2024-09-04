<?php

use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\RecruitmentEventController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/job-applications', [JobApplicationController::class, 'index']);
    Route::post('/job-applications', [JobApplicationController::class, 'store']);
    Route::put('/job-applications/{id}', [JobApplicationController::class, 'update']);
    Route::delete('/job-applications/{id}', [JobApplicationController::class, 'delete']);


    Route::get('/recruitment-events', [RecruitmentEventController::class, 'index']);
    Route::get('/job-applications/{jobApplicationId}/recruitment-events', [RecruitmentEventController::class, 'index']);
    Route::post('/recruitment-events', [RecruitmentEventController::class, 'store']);
});
