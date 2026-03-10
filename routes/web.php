<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\BroadcastController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\FinancialController;
use App\Http\Controllers\Web\InformatinController;
use App\Http\Controllers\Web\InformationController;
use App\Http\Controllers\Web\LearningController;
use App\Http\Controllers\Web\MemberRegistrationController;
use App\Http\Controllers\Web\OrganizationController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\SocialController;
use App\Http\Controllers\Web\TicketController;
use App\Http\Controllers\Web\UnionController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\VisionController;
use App\Http\Controllers\Web\VoteController;
use Illuminate\Support\Facades\Route;


// Routes untuk guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Routes untuk auth (sudah login)
Route::middleware(['auth'])->group(function () {
    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Route
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Route
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
    });

    // Users Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Informations Routes
    Route::prefix('informations')->name('informations.')->group(function () {
        Route::get('/', [InformationController::class, 'index'])->name('index');
        Route::get('/create', [InformationController::class, 'create'])->name('create');
        Route::post('/store', [InformationController::class, 'store'])->name('store');
        Route::get('/{information}', [InformationController::class, 'show'])->name('show');
        Route::get('/{information}/edit', [InformationController::class, 'edit'])->name('edit');
        Route::put('/{information}', [InformationController::class, 'update'])->name('update');
        Route::delete('/{information}', [InformationController::class, 'destroy'])->name('destroy');
    });

    // Organizations Routes
    Route::prefix('organizations')->name('organizations.')->group(function () {
        Route::get('/', [OrganizationController::class, 'index'])->name('index');
        Route::get('/create', [OrganizationController::class, 'create'])->name('create');
        Route::post('/store', [OrganizationController::class, 'store'])->name('store');
        Route::get('/{organization}', [OrganizationController::class, 'show'])->name('show');
        Route::get('/{organization}/edit', [OrganizationController::class, 'edit'])->name('edit');
        Route::put('/{organization}', [OrganizationController::class, 'update'])->name('update');
        Route::delete('/{organization}', [OrganizationController::class, 'destroy'])->name('destroy');
    });

    // Socials Routes
    Route::prefix('socials')->name('socials.')->group(function () {
        Route::get('/', [SocialController::class, 'index'])->name('index');
        Route::get('/create', [SocialController::class, 'create'])->name('create');
        Route::post('/store', [SocialController::class, 'store'])->name('store');
        Route::get('/{social}', [SocialController::class, 'show'])->name('show');
        Route::get('/{social}/edit', [SocialController::class, 'edit'])->name('edit');
        Route::put('/{social}', [SocialController::class, 'update'])->name('update');
        Route::delete('/{social}', [SocialController::class, 'destroy'])->name('destroy');
    });

    // Learnings Routes
    Route::prefix('learnings')->name('learnings.')->group(function () {
        Route::get('/', [LearningController::class, 'index'])->name('index');
        Route::get('/create', [LearningController::class, 'create'])->name('create');
        Route::post('/store', [LearningController::class, 'store'])->name('store');
        Route::get('/{learning}', [LearningController::class, 'show'])->name('show');
        Route::get('/{learning}/edit', [LearningController::class, 'edit'])->name('edit');
        Route::put('/{learning}', [LearningController::class, 'update'])->name('update');
        Route::delete('/{learning}', [LearningController::class, 'destroy'])->name('destroy');
    });

    // Financials Routes
    Route::prefix('financials')->name('financials.')->group(function () {
        Route::get('/', [FinancialController::class, 'index'])->name('index');
        Route::get('/create', [FinancialController::class, 'create'])->name('create');
        Route::post('/store', [FinancialController::class, 'store'])->name('store');
        Route::get('/{financial}', [FinancialController::class, 'show'])->name('show');
        Route::get('/{financial}/edit', [FinancialController::class, 'edit'])->name('edit');
        Route::put('/{financial}', [FinancialController::class, 'update'])->name('update');
        Route::delete('/{financial}', [FinancialController::class, 'destroy'])->name('destroy');
    });

    // Unions Routes
    Route::prefix('unions')->name('unions.')->group(function () {
        Route::get('/', [UnionController::class, 'index'])->name('index');
        Route::get('/create', [UnionController::class, 'create'])->name('create');
        Route::post('/store', [UnionController::class, 'store'])->name('store');
        Route::get('/{union}', [UnionController::class, 'show'])->name('show');
        Route::get('/{union}/edit', [UnionController::class, 'edit'])->name('edit');
        Route::put('/{union}', [UnionController::class, 'update'])->name('update');
        Route::delete('/{union}', [UnionController::class, 'destroy'])->name('destroy');
    });

    // Votes Routes
    Route::prefix('votes')->name('votes.')->group(function () {
        Route::get('/', [VoteController::class, 'index'])->name('index');
        Route::get('/create', [VoteController::class, 'create'])->name('create');
        Route::post('/store', [VoteController::class, 'store'])->name('store');
        Route::get('/{vote}', [VoteController::class, 'show'])->name('show');
        Route::get('/{vote}/edit', [VoteController::class, 'edit'])->name('edit');
        Route::put('/{vote}', [VoteController::class, 'update'])->name('update');
        Route::delete('/{vote}', [VoteController::class, 'destroy'])->name('destroy');
    });

    // Tickets Routes
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('/{ticket}', [TicketController::class, 'show'])->name('show');
        Route::get('/{ticket}/edit', [TicketController::class, 'edit'])->name('edit');
        Route::post('/{ticket}/reply', [TicketController::class, 'reply'])->name('reply');
        Route::put('/{ticket}', [TicketController::class, 'update'])->name('update');
        Route::delete('/{ticket}', [TicketController::class, 'destroy'])->name('destroy');
        Route::get('/{ticket}/pdf-preview', [TicketController::class, 'previewPdf'])->name('pdf');
    });

    // Member Member Registrations Routes
    Route::prefix('members')->name('members.')->group(function () {
        Route::get('/', [MemberRegistrationController::class, 'index'])->name('index');
        Route::get('/{member}', [MemberRegistrationController::class, 'show'])->name('show');
        Route::get('/{member}/edit', [MemberRegistrationController::class, 'edit'])->name('edit');
        Route::put('/{member}', [MemberRegistrationController::class, 'update'])->name('update');
        Route::post('/{member}/approve', [MemberRegistrationController::class, 'approve'])->name('approve');
        Route::get('/{member}/pdf-preview', [MemberRegistrationController::class, 'previewPdf'])->name('pdf');
    });

    // Broadcasts Routes
    Route::prefix('broadcasts')->name('broadcasts.')->group(function () {
        Route::get('/', [BroadcastController::class, 'index'])->name('index');
        Route::get('/create', [BroadcastController::class, 'create'])->name('create');
        Route::post('/store', [BroadcastController::class, 'store'])->name('store');
        Route::delete('/{broadcast}', [BroadcastController::class, 'destroy'])->name('destroy');
    });

    // Vision Mission Routes
    Route::prefix('vision')->name('vision.')->group(function () {
        Route::get('/edit', [VisionController::class, 'edit'])->name('edit');
        Route::put('/update', [VisionController::class, 'update'])->name('update');
    });
});
