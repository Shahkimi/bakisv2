<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\CarianController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\JawatanController;
use App\Http\Controllers\Admin\Kawalan\FaviconController;
use App\Http\Controllers\Admin\KutipanController as AdminKutipanController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\PaymentAccountController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\YuranController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PostcodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SemakController;
use App\Http\Controllers\User\KutipanController as UserKutipanController;
use App\Models\Yuran;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }

    $yurans = Yuran::query()
        ->where('is_active', true)
        ->where('is_show', true)
        ->orderBy('jenis_yuran', 'desc')
        ->get(['jenis_yuran', 'jumlah', 'tempoh_tahun', 'is_active', 'is_show']);

    return view('welcome', compact('yurans'));
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/password/forgot-by-kp', [ForgotPasswordController::class, 'sendResetLink'])
    ->middleware(['guest', 'throttle:5,1'])
    ->name('password.forgot-by-kp');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
    ->middleware(['guest', 'throttle:10,1'])
    ->name('password.update');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::put('/profile/email', [ProfileController::class, 'updateEmail'])->name('profile.email');
});

Route::middleware('throttle:semak')->group(function () {
    Route::get('/semak', [SemakController::class, 'index'])->name('semak.index');
    Route::post('/semak', [SemakController::class, 'check'])->name('semak.check');
    Route::get('/semak/result', [SemakController::class, 'showResult'])->name('semak.result');
    Route::get('/semak/qr/{paymentAccount}', [SemakController::class, 'showQr'])->name('semak.qr')->middleware('signed');
    Route::post('/semak/bayar', [SemakController::class, 'bayar'])->name('semak.bayar');
    Route::post('/semak/daftar', [SemakController::class, 'register'])->name('semak.register');
    Route::get('/semak/success', [SemakController::class, 'success'])->name('semak.success');
});

Route::get('/invitation/accept/{token}', [InvitationController::class, 'show'])
    ->middleware(['signed', 'throttle:20,1'])
    ->name('invitation.accept');

Route::post('/invitation/accept/{token}', [InvitationController::class, 'store'])
    ->middleware(['throttle:10,1'])
    ->name('invitation.accept.store');

Route::middleware(['auth', 'role.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('carian', fn () => redirect()->route('admin.members.index'))->name('carian.index');
    Route::get('carian/data', [CarianController::class, 'getData'])->name('carian.data');
    Route::resource('members', AdminMemberController::class);

    Route::prefix('kutipan')->name('kutipan.')->group(function () {
        Route::get('/', [AdminKutipanController::class, 'index'])->name('index');
        Route::get('/autocomplete', [AdminKutipanController::class, 'autocomplete'])->name('autocomplete');
        Route::get('/member/{encryptedNoKp}', [AdminKutipanController::class, 'member'])->name('member');
        Route::post('/collect', [AdminKutipanController::class, 'collect'])->name('collect');
        Route::post('/collect-multi-year', [AdminKutipanController::class, 'collectMultiYear'])->name('collect-multi-year');
    });

    Route::get('pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('pembayaran/data', [PembayaranController::class, 'getData'])->name('pembayaran.data');
    Route::get('pembayaran/pending-count', [PembayaranController::class, 'getPendingCount'])->name('pembayaran.pending-count');
    Route::get('pembayaran/{payment}/bukti', [PembayaranController::class, 'bukti'])->name('pembayaran.bukti');
    Route::post('pembayaran/{payment}/approve', [PembayaranController::class, 'approve'])->name('pembayaran.approve');
    Route::post('pembayaran/{payment}/reject', [PembayaranController::class, 'reject'])->name('pembayaran.reject');

    Route::prefix('kawalan/pengguna')->name('kawalan.pengguna.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('data', [UserController::class, 'getData'])->name('data');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::put('user/{user}', [UserController::class, 'update'])->name('user.update');
        Route::delete('user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::delete('jemputan/{invitation}', [UserController::class, 'destroyInvitation'])->name('invitation.destroy');
        Route::post('jemputan/{invitation}/hantar', [UserController::class, 'resendInvitation'])->name('invitation.resend');
    });

    Route::prefix('kawalan/jabatan')->name('kawalan.jabatan.')->group(function () {
        Route::get('/', [JabatanController::class, 'index'])->name('index');
        Route::get('data', [JabatanController::class, 'getData'])->name('data');
        Route::post('/', [JabatanController::class, 'store'])->name('store');
        Route::put('{jabatan}', [JabatanController::class, 'update'])->name('update');
        Route::delete('{jabatan}', [JabatanController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('kawalan/jawatan')->name('kawalan.jawatan.')->group(function () {
        Route::get('/', [JawatanController::class, 'index'])->name('index');
        Route::get('data', [JawatanController::class, 'getData'])->name('data');
        Route::post('/', [JawatanController::class, 'store'])->name('store');
        Route::put('{jawatan}', [JawatanController::class, 'update'])->name('update');
        Route::delete('{jawatan}', [JawatanController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('kawalan/yuran')->name('kawalan.yuran.')->group(function () {
        Route::get('/', [YuranController::class, 'index'])->name('index');
        Route::get('data', [YuranController::class, 'getData'])->name('data');
        Route::post('/', [YuranController::class, 'store'])->name('store');
        Route::put('{yuran}', [YuranController::class, 'update'])->name('update');
        Route::delete('{yuran}', [YuranController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('kawalan/account')->name('kawalan.account.')->group(function () {
        Route::get('/', [PaymentAccountController::class, 'index'])->name('index');
        Route::get('data', [PaymentAccountController::class, 'getData'])->name('data');
        Route::get('{paymentAccount}/qr', [PaymentAccountController::class, 'showQr'])->name('qr');
        Route::post('/', [PaymentAccountController::class, 'store'])->name('store');
        Route::put('{paymentAccount}', [PaymentAccountController::class, 'update'])->name('update');
        Route::delete('{paymentAccount}', [PaymentAccountController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('kawalan/favicon')->name('kawalan.favicon.')->group(function () {
        Route::get('/', [FaviconController::class, 'index'])->name('index');
        Route::post('/', [FaviconController::class, 'store'])->name('store');
        Route::delete('/', [FaviconController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware(['auth', 'role.user'])->prefix('user')->name('user.')->group(function () {
    Route::get('carian', fn () => redirect()->route('user.members.index'))->name('carian.index');
    Route::get('carian/data', [CarianController::class, 'getData'])->name('carian.data');
    Route::resource('members', AdminMemberController::class);

    Route::prefix('kutipan')->name('kutipan.')->group(function () {
        Route::get('/', [UserKutipanController::class, 'index'])->name('index');
        Route::get('/autocomplete', [UserKutipanController::class, 'autocomplete'])->name('autocomplete');
        Route::get('/member/{encryptedNoKp}', [UserKutipanController::class, 'member'])->name('member');
        Route::post('/collect', [UserKutipanController::class, 'collect'])->name('collect');
        Route::post('/collect-multi-year', [UserKutipanController::class, 'collectMultiYear'])->name('collect-multi-year');
    });

    Route::get('pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('pembayaran/data', [PembayaranController::class, 'getData'])->name('pembayaran.data');
    Route::get('pembayaran/pending-count', [PembayaranController::class, 'getPendingCount'])->name('pembayaran.pending-count');
    Route::get('pembayaran/{payment}/bukti', [PembayaranController::class, 'bukti'])->name('pembayaran.bukti');
    Route::post('pembayaran/{payment}/approve', [PembayaranController::class, 'approve'])->name('pembayaran.approve');
    Route::post('pembayaran/{payment}/reject', [PembayaranController::class, 'reject'])->name('pembayaran.reject');
});

Route::get('/api/postcode/{code}', [PostcodeController::class, 'lookup'])
    ->middleware('throttle:60,1')
    ->name('api.postcode.lookup');
