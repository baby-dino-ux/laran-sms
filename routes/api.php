<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AwardController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ScholarshipController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Scholarship Management System (SMS)
|--------------------------------------------------------------------------
*/

// ─── Public: Authentication ───────────────────────────────────────────────────
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// ─── Protected Routes ─────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // ── User Profile ──────────────────────────────────────────────────────────
    Route::get('/profile',                [UserController::class, 'profile']);
    Route::put('/profile',                [UserController::class, 'updateProfile']);
    Route::post('/profile/picture',       [UserController::class, 'uploadProfilePicture']);

    // ── Admin: User Management ────────────────────────────────────────────────
    Route::get('/users',                  [UserController::class, 'index']);
    Route::get('/users/{user}',           [UserController::class, 'show']);
    Route::put('/users/{user}',           [UserController::class, 'update']);
    Route::delete('/users/{user}',        [UserController::class, 'destroy']);
    Route::patch('/users/{user}/role',    [UserController::class, 'assignRole']);

    // ── Scholarships ──────────────────────────────────────────────────────────
    Route::get('/scholarships',                             [ScholarshipController::class, 'index']);
    Route::get('/scholarships/{scholarship}',               [ScholarshipController::class, 'show']);
    Route::post('/scholarships',                            [ScholarshipController::class, 'store']);
    Route::put('/scholarships/{scholarship}',               [ScholarshipController::class, 'update']);
    Route::delete('/scholarships/{scholarship}',            [ScholarshipController::class, 'destroy']);
    Route::put('/scholarships/{scholarship}/eligibility',   [ScholarshipController::class, 'setEligibility']);

    // ── Applications ──────────────────────────────────────────────────────────
    Route::get('/applications',                              [ApplicationController::class, 'index']);
    Route::post('/applications',                             [ApplicationController::class, 'store']);
    Route::get('/applications/{application}',                [ApplicationController::class, 'show']);
    Route::put('/applications/{application}',                [ApplicationController::class, 'update']);
    Route::get('/applications/{application}/status',         [ApplicationController::class, 'status']);
    Route::post('/applications/{application}/submit',        [ApplicationController::class, 'submit']);
    Route::post('/applications/{application}/review',        [ApplicationController::class, 'review']);
    Route::post('/applications/{application}/approve',       [ApplicationController::class, 'approve']);
    Route::post('/applications/{application}/reject',        [ApplicationController::class, 'reject']);

    // ── Documents ─────────────────────────────────────────────────────────────
    Route::get('/documents',                  [DocumentController::class, 'index']);
    Route::post('/documents',                 [DocumentController::class, 'store']);
    Route::get('/documents/{document}',       [DocumentController::class, 'show']);
    Route::get('/documents/{document}/download', [DocumentController::class, 'download']);
    Route::delete('/documents/{document}',    [DocumentController::class, 'destroy']);

    // ── Awards ────────────────────────────────────────────────────────────────
    Route::get('/awards',                              [AwardController::class, 'index']);
    Route::post('/awards',                             [AwardController::class, 'store']);
    Route::get('/awards/history',                      [AwardController::class, 'history']);
    Route::get('/awards/{award}',                      [AwardController::class, 'show']);
    Route::post('/awards/{award}/notify',              [AwardController::class, 'sendNotification']);

    // ── Notifications ─────────────────────────────────────────────────────────
    Route::get('/notifications',                           [NotificationController::class, 'index']);
    Route::get('/notifications/{notification}',            [NotificationController::class, 'show']);
    Route::post('/notifications/send',                     [NotificationController::class, 'send']);
    Route::patch('/notifications/{notification}/read',     [NotificationController::class, 'markRead']);
    Route::patch('/notifications/read-all',                [NotificationController::class, 'markAllRead']);
    Route::delete('/notifications/{notification}',         [NotificationController::class, 'destroy']);

    // ── Reports ───────────────────────────────────────────────────────────────
    Route::get('/reports/dashboard',     [ReportController::class, 'dashboard']);
    Route::get('/reports/applications',  [ReportController::class, 'applicationReport']);
    Route::get('/reports/awards',        [ReportController::class, 'awardReport']);
});
