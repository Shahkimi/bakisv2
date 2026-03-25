<?php

use App\Http\Controllers\Admin\CarianController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\JawatanController;
use App\Http\Controllers\Admin\KutipanController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\PaymentAccountController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\YuranController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PostcodeController;
use App\Http\Controllers\SemakController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }

    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('throttle:3,10')->group(function () {
    Route::get('/semak', [SemakController::class, 'index'])->name('semak.index');
    Route::post('/semak', [SemakController::class, 'check'])->name('semak.check');
    Route::get('/semak/result', [SemakController::class, 'showResult'])->name('semak.result');
    Route::get('/semak/qr/{paymentAccount}', [SemakController::class, 'showQr'])->name('semak.qr')->middleware('signed');
    Route::post('/semak/bayar', [SemakController::class, 'bayar'])->name('semak.bayar');
    Route::post('/semak/daftar', [SemakController::class, 'register'])->name('semak.register');
    Route::get('/semak/success', [SemakController::class, 'success'])->name('semak.success');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('carian', fn () => redirect()->route('admin.members.index'))->name('carian.index');
    Route::get('carian/data', [CarianController::class, 'getData'])->name('carian.data');
    Route::resource('members', AdminMemberController::class);

    Route::prefix('kutipan')->name('kutipan.')->group(function () {
        Route::get('/', [KutipanController::class, 'index'])->name('index');
        Route::get('/autocomplete', [KutipanController::class, 'autocomplete'])->name('autocomplete');
        Route::get('/member/{encryptedNoKp}', [KutipanController::class, 'member'])->name('member');
        Route::post('/collect', [KutipanController::class, 'collect'])->name('collect');
    });

    Route::get('pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('pembayaran/data', [PembayaranController::class, 'getData'])->name('pembayaran.data');
    Route::get('pembayaran/pending-count', [PembayaranController::class, 'getPendingCount'])->name('pembayaran.pending-count');
    Route::get('pembayaran/{payment}/bukti', [PembayaranController::class, 'bukti'])->name('pembayaran.bukti');
    Route::post('pembayaran/{payment}/approve', [PembayaranController::class, 'approve'])->name('pembayaran.approve');
    Route::post('pembayaran/{payment}/reject', [PembayaranController::class, 'reject'])->name('pembayaran.reject');
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
});

Route::get('/api/postcode/{code}', [PostcodeController::class, 'lookup'])
    ->middleware('throttle:60,1')
    ->name('api.postcode.lookup');
