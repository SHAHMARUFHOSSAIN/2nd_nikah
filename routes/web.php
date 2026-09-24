<?php

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\Dashboard;
use App\Livewire\Home;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Home & Discovery Routes
Route::get('/', Home::class)->name('home');
Route::get('/members', \App\Livewire\Members\Index::class)->name('members.index');
Route::get('/search', \App\Livewire\Members\Search::class)->name('search.index');
Route::get('/members/{memberProfile}', \App\Livewire\Members\Show::class)->name('members.show');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
});

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/member/profile', \App\Livewire\Member\Profile::class)->middleware('verified')->name('member.profile');

    // Email Verification Routes
    Route::get('/email/verify', VerifyEmail::class)->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('dashboard')->with('status', 'Email verified successfully!');
    })->middleware('signed')->name('verification.verify');

    // Logout Route
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    })->name('logout');
});
