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

// Public Home, Discovery & Membership Routes
Route::get('/', Home::class)->name('home');
Route::get('/members', \App\Livewire\Members\Index::class)->name('members.index');
Route::get('/search', \App\Livewire\Members\Search::class)->name('search.index');
Route::get('/members/{memberProfile}', \App\Livewire\Members\Show::class)->name('members.show');
Route::get('/membership', \App\Livewire\Membership\Index::class)->name('membership.index');

// SSLCommerz Payment Callbacks (Exempt from CSRF)
Route::post('/payment/success', [\App\Http\Controllers\PaymentCallbackController::class, 'success'])->name('payment.success');
Route::post('/payment/fail', [\App\Http\Controllers\PaymentCallbackController::class, 'fail'])->name('payment.fail');
Route::post('/payment/cancel', [\App\Http\Controllers\PaymentCallbackController::class, 'cancel'])->name('payment.cancel');
Route::post('/payment/ipn', [\App\Http\Controllers\PaymentCallbackController::class, 'ipn'])->name('payment.ipn');

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

    // Member Interests, Connections & Membership (Phases 4 & 5)
    Route::middleware('verified')->group(function () {
        Route::get('/member/interests/received', \App\Livewire\Member\Interests\Received::class)->name('member.interests.received');
        Route::get('/member/interests/sent', \App\Livewire\Member\Interests\Sent::class)->name('member.interests.sent');
        Route::get('/member/connections', \App\Livewire\Member\Connections\Index::class)->name('member.connections');
        Route::get('/membership/checkout', \App\Livewire\Membership\Checkout::class)->name('membership.checkout');
        Route::get('/member/payments', \App\Livewire\Member\Payments::class)->name('member.payments');
    });

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
