<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

// Attendance workflow routes (protected by sanctum, gate checks in controllers)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/attendance/sessions', [\App\Http\Controllers\API\AttendanceController::class, 'create']);
    Route::post('/attendance/sessions/{id}/open', [\App\Http\Controllers\API\AttendanceController::class, 'open']);
    Route::post('/attendance/sessions/{id}/records', [\App\Http\Controllers\API\AttendanceController::class, 'records']);
    Route::post('/attendance/sessions/{id}/close', [\App\Http\Controllers\API\AttendanceController::class, 'close']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/grades/upsert', [\App\Http\Controllers\API\GradesController::class, 'upsert']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/payments/{id}/verify', [\App\Http\Controllers\API\FinanceController::class, 'verify']);
});
