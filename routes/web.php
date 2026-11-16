<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ProposalManagementController;
use App\Http\Controllers\Admin\ReportVerificationController;
use App\Http\Controllers\Admin\SchemeController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AccountRegistrationController;
use App\Http\Controllers\Admin\AccountRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboard;
use App\Http\Controllers\Dosen\FinalReportController;
use App\Http\Controllers\Dosen\LogbookController;
use App\Http\Controllers\Dosen\ProgressReportController;
use App\Http\Controllers\Dosen\ProposalController as DosenProposalController;
use App\Http\Controllers\Reviewer\DashboardController as ReviewerDashboard;
use App\Http\Controllers\Reviewer\MonevController;
use App\Http\Controllers\Reviewer\ReviewController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/daftar-akun', [AccountRegistrationController::class, 'create'])->name('register');
    Route::post('/daftar-akun', [AccountRegistrationController::class, 'store'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');

    // Redirect dashboard sesuai role pengguna
    Route::get('/dashboard', function () {
        $role = Auth::user()->role;
        return match ($role) {
            'admin', 'lppm' => redirect()->route('admin.dashboard'),
            'reviewer' => redirect()->route('reviewer.dashboard'),
            default    => redirect()->route('dosen.dashboard'),
        };
    })->name('dashboard');

    // ==================== PANEL DOSEN (PENGUSUL) ====================
    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [DosenDashboard::class, 'index'])->name('dashboard');
        Route::get('/schemes/{scheme}/template', [DosenProposalController::class, 'downloadTemplate'])->name('schemes.template');
        Route::resource('proposals', DosenProposalController::class)->only(['index', 'create', 'store', 'show']);
        Route::post('/proposals/{proposal}/submit', [DosenProposalController::class, 'submitDraft'])->name('proposals.submit');
        Route::post('/proposals/{proposal}/revision', [DosenProposalController::class, 'uploadRevision'])->name('proposals.revision');
        Route::post('/proposals/{proposal}/logbooks', [LogbookController::class, 'store'])->name('proposals.logbooks.store');
        Route::post('/proposals/{proposal}/progress-report', [ProgressReportController::class, 'store'])->name('proposals.progress.store');
        Route::post('/proposals/{proposal}/final-report', [FinalReportController::class, 'store'])->name('proposals.final.store');
    });

    // ==================== PANEL REVIEWER ====================
    Route::middleware('role:reviewer')->prefix('reviewer')->name('reviewer.')->group(function () {
        Route::get('/dashboard', [ReviewerDashboard::class, 'index'])->name('dashboard');
        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
        Route::post('/reviews/{review}/score', [ReviewController::class, 'submitScore'])->name('reviews.score');
        Route::post('/monev/{progressReport}', [MonevController::class, 'evaluate'])->name('monev.evaluate');
    });

    // ==================== PANEL OPERASIONAL ADMIN & LPPM ====================
    Route::middleware('role:admin,lppm')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::get('/schemes', [SchemeController::class, 'index'])->name('schemes.index');
        Route::post('/schemes', [SchemeController::class, 'store'])->name('schemes.store');
        Route::post('/schemes/{scheme}/template', [SchemeController::class, 'updateTemplate'])->name('schemes.template');
        Route::delete('/schemes/{scheme}', [SchemeController::class, 'destroy'])->name('schemes.destroy');

        Route::get('/proposals', [ProposalManagementController::class, 'index'])->name('proposals.index');
        Route::get('/proposals/{proposal}', [ProposalManagementController::class, 'show'])->name('proposals.show');
        Route::post('/proposals/{proposal}/verify', [ProposalManagementController::class, 'verifyAndAssignReviewer'])->name('proposals.verify');
        Route::post('/proposals/{proposal}/decide', [ProposalManagementController::class, 'decideFunding'])->name('proposals.decide');
        Route::post('/proposals/{proposal}/approve-revision', [ProposalManagementController::class, 'approveRevision'])->name('proposals.approve-revision');
        Route::post('/proposals/{proposal}/verify-final', [ReportVerificationController::class, 'verifyFinalReport'])->name('proposals.verify-final');

        // Hanya Admin yang boleh mengelola akun dan memberikan role.
        Route::middleware('role:admin')->group(function () {
            Route::get('/permintaan-akun', [AccountRequestController::class, 'index'])->name('accounts.requests');
            Route::post('/permintaan-akun/{user}/setujui', [AccountRequestController::class, 'approve'])->name('accounts.approve');
            Route::post('/permintaan-akun/{user}/tolak', [AccountRequestController::class, 'reject'])->name('accounts.reject');
            Route::get('/pengguna', [UserManagementController::class, 'index'])->name('users.index');
            Route::get('/pengguna/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
            Route::put('/pengguna/{user}', [UserManagementController::class, 'update'])->name('users.update');
        });
    });
});
