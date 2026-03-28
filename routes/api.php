<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ScholarshipController;
use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\API\DocumentController;
use App\Http\Controllers\API\AwardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Scholarship Management System
|--------------------------------------------------------------------------
|
| Auth    → /api/register | /api/login | /api/logout | /api/me
| CRUD    → Scholarships, Applications, Documents, Awards
|
*/

// ── Public Routes (No Auth Required) ──────────────────────────────────────
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// ── Protected Routes (Sanctum Token Required) ─────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Scholarships — Admin: full CRUD | Student: read only
    Route::apiResource('scholarships', ScholarshipController::class);

    // Applications — Admin: full CRUD | Student: own only
    Route::apiResource('applications', ApplicationController::class);

    // Documents — nested under applications for upload
    Route::get('applications/{application_id}/documents',        [DocumentController::class, 'index']);
    Route::post('applications/{application_id}/documents',       [DocumentController::class, 'store']);
    Route::get('documents/{id}',                                 [DocumentController::class, 'show']);
    Route::delete('documents/{id}',                              [DocumentController::class, 'destroy']);

    // Awards — Admin: full CRUD | Student: read own
    Route::apiResource('awards', AwardController::class);
});