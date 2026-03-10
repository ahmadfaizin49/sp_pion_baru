<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FeedController;
use App\Http\Controllers\Api\InformationController;
use App\Http\Controllers\Api\FinancialController;
use App\Http\Controllers\Api\LearningController;
use App\Http\Controllers\Api\MemberRegistrationController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\SocialController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\UnionController;
use App\Http\Controllers\Api\VisionController;
use App\Http\Controllers\Api\VoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// ==============================
// Routes Public (belum login)
// ==============================
Route::prefix('auth')->group(function () {
    // ✅ ROUTE UNTUK AUTHENTICATION
    Route::post('/login', [AuthController::class, 'login']);

    // ✅ ROUTE UNTUK FORGOT PASSWORD
    // STEP 1 — Request OTP
    Route::post('/request-otp', [ResetPasswordController::class, 'requestOtp'])->middleware('throttle:3,5');
    // STEP 2 — Verify OTP (generate reset_token)
    Route::post('/verify-otp', [ResetPasswordController::class, 'verifyOtp']);
    // STEP 3 — Reset password pakai reset_token
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);
});

// ==============================
// Routes Protected (sudah login)
// ==============================
Route::middleware('auth:sanctum')->group(function () {

    // ----- Auth Route -----
    Route::prefix('auth')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);

        Route::post('/update-profile', [AuthController::class, 'updateProfile']);
        Route::post('/update-password', [AuthController::class, 'updatePassword']);
        Route::post('/update-pin', [AuthController::class, 'updatePin']);

        Route::post('/fcm-token', [AuthController::class, 'fcmToken']);
        Route::post('/verify-pin', [AuthController::class, 'verifyPin']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    // ----- Informations Route -----
    Route::prefix('informations')->group(function () {
        Route::get('/', [InformationController::class, 'index']);
        Route::get('/{information}', [InformationController::class, 'show']);
    });

    // ----- Financials Route -----
    Route::prefix('financials')->group(function () {
        Route::get('/', [FinancialController::class, 'index']);
        Route::get('/{financial}', [FinancialController::class, 'show']);
    });

    // ----- Learnings Route -----
    Route::prefix('learnings')->group(function () {
        Route::get('/', [LearningController::class, 'index']);
        Route::get('/{learning}', [LearningController::class, 'show']);
    });

    // ----- Organizations Route -----
    Route::prefix('organizations')->group(function () {
        Route::get('/', [OrganizationController::class, 'index']);
        Route::get('/{organization}', [OrganizationController::class, 'show']);
    });

    // ----- Socials Route -----
    Route::prefix('socials')->group(function () {
        Route::get('/', [SocialController::class, 'index']);
        Route::get('/{social}', [SocialController::class, 'show']);
    });

    // ----- Unions Route -----
    Route::prefix('unions')->group(function () {
        Route::get('/', [UnionController::class, 'index']);
        Route::get('/{union}', [UnionController::class, 'show']);
    });

    // ----- Ticket Route -----
    Route::prefix('tickets')->group(function () {
        Route::get('/', [TicketController::class, 'index']);
        Route::get('/{ticket}', [TicketController::class, 'show']);
        Route::post('/', [TicketController::class, 'store']);
        Route::put('/{ticket}', [TicketController::class, 'update']);
        Route::post('/{ticket}/reply', [TicketController::class, 'reply']);
    });

    // ----- Vote Route -----
    Route::prefix('votes')->group(function () {
        Route::get('/', [VoteController::class, 'index']);
        Route::get('/{vote}', [VoteController::class, 'show']);
        Route::post('/', [VoteController::class, 'store']);
    });

    // ----- Feed Route -----
    Route::get('/feed', [FeedController::class, 'index']);

    // ----- Vision Route -----
    Route::get('/vision', [VisionController::class, 'show']);

    // ----- Member Registration Route -----
    Route::prefix('members')->group(function () {
        Route::get('/', [MemberRegistrationController::class, 'index']);
        Route::get('/{member}', [MemberRegistrationController::class, 'show']);
        Route::post('/', [MemberRegistrationController::class, 'store']);
    });
});
