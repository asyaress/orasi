<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrasiController;
use App\Http\Controllers\Admin\AdminPengumumanController;
use App\Http\Controllers\Admin\AdminStatisticsController;
use App\Http\Controllers\Admin\AdminArsipController;
use App\Http\Controllers\Admin\AdminOrasiIlmiahController;
use App\Http\Controllers\Admin\AdminGuruBesarController;
use App\Http\Controllers\Admin\AdminOrasiGuruBesarController;
use App\Http\Controllers\Admin\AdminFakultasController;
use App\Http\Controllers\Admin\AdminProdiController;
use App\Http\Controllers\Admin\AdminSecurityController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\TwoFactorSetupController;
use App\Http\Controllers\Auth\TwoFactorChallengeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/chat/orasi', [HomeController::class, 'chatbotReply'])->name('home.chatbot');
Route::get('/guru-besar', [HomeController::class, 'guruBesar'])->name('portal.guru-besar');
Route::get('/guru-besar/{guruBesar}', [HomeController::class, 'guruBesarShow'])->name('portal.guru-besar.show');
Route::get('/guru-besar/{guruBesar}/unduh-paket', [HomeController::class, 'downloadGuruBesarPackage'])->name('portal.guru-besar.download');
Route::get('/daftar-orasi', [HomeController::class, 'daftarOrasi'])->name('portal.daftar-orasi');
Route::get('/video-orasi', [HomeController::class, 'videoOrasi'])->name('portal.video-orasi');
Route::get('/dokumen-orasi', [HomeController::class, 'dokumenOrasi'])->name('portal.dokumen-orasi');
Route::get('/dokumen-orasi/tahun/{year}/unduh-gabungan/{type}', [HomeController::class, 'downloadMergedDocumentsByYear'])
    ->where('year', '[0-9]+|tanpa-tahun')
    ->where('type', 'naskah|presentasi')
    ->name('portal.dokumen-orasi.merge');
Route::get('/statistik', [HomeController::class, 'statistik'])->name('portal.statistik');
Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('portal.pengumuman.index');
Route::get('/pengumuman/{pengumuman:slug}', [PengumumanController::class, 'show'])->name('portal.pengumuman.show');

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/two-factor/setup', [TwoFactorSetupController::class, 'show'])->name('two-factor.setup');
    Route::post('/two-factor/setup', [TwoFactorSetupController::class, 'confirm'])->name('two-factor.confirm');
});

Route::get('/two-factor/challenge', [TwoFactorChallengeController::class, 'show'])->name('two-factor.challenge');
Route::post('/two-factor/challenge', [TwoFactorChallengeController::class, 'store'])->name('two-factor.challenge.store');

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::middleware(['auth', 'admin', '2fa.confirmed'])->group(function () {
            Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

            // Orasi Ilmiah (event)
            Route::get('/orasi-ilmiah', [AdminOrasiIlmiahController::class, 'index'])->name('orasi-ilmiah.index');
            Route::get('/orasi-ilmiah/create', [AdminOrasiIlmiahController::class, 'create'])->name('orasi-ilmiah.create');
            Route::post('/orasi-ilmiah', [AdminOrasiIlmiahController::class, 'store'])->name('orasi-ilmiah.store');
            Route::get('/orasi-ilmiah/{orasiIlmiah}', [AdminOrasiIlmiahController::class, 'show'])->name('orasi-ilmiah.show');
            Route::get('/orasi-ilmiah/{orasiIlmiah}/edit', [AdminOrasiIlmiahController::class, 'edit'])->name('orasi-ilmiah.edit');
            Route::put('/orasi-ilmiah/{orasiIlmiah}', [AdminOrasiIlmiahController::class, 'update'])->name('orasi-ilmiah.update');

            // Penugasan Guru Besar ke Orasi tahun tertentu
            Route::post('/orasi-ilmiah/{orasiIlmiah}/guru-besar/attach', [AdminOrasiGuruBesarController::class, 'attach'])->name('orasi-ilmiah.guru-besar.attach');
            Route::delete('/orasi-ilmiah/{orasiIlmiah}/guru-besar/{guruBesar}/detach', [AdminOrasiGuruBesarController::class, 'detach'])->name('orasi-ilmiah.guru-besar.detach');

            // Master data Guru Besar (kepegawaian + manual)
            Route::get('/guru-besar', [AdminGuruBesarController::class, 'index'])->name('guru-besar.index');
            Route::get('/guru-besar/create', [AdminGuruBesarController::class, 'create'])->name('guru-besar.create');
            Route::post('/guru-besar', [AdminGuruBesarController::class, 'store'])->name('guru-besar.store');
            Route::get('/guru-besar/{guruBesar}/edit', [AdminGuruBesarController::class, 'edit'])->name('guru-besar.edit');
            Route::put('/guru-besar/{guruBesar}', [AdminGuruBesarController::class, 'update'])->name('guru-besar.update');
            Route::delete('/guru-besar/{guruBesar}', [AdminGuruBesarController::class, 'destroy'])->name('guru-besar.destroy');

            Route::get('/statistics', [AdminStatisticsController::class, 'index'])->name('statistics.index');

            Route::get('/pengumuman', [AdminPengumumanController::class, 'index'])->name('pengumuman.index');
            Route::get('/pengumuman/create', [AdminPengumumanController::class, 'create'])->name('pengumuman.create');
            Route::post('/pengumuman', [AdminPengumumanController::class, 'store'])->name('pengumuman.store');
            Route::get('/pengumuman/{pengumuman}/edit', [AdminPengumumanController::class, 'edit'])->name('pengumuman.edit');
            Route::put('/pengumuman/{pengumuman}', [AdminPengumumanController::class, 'update'])->name('pengumuman.update');
            Route::delete('/pengumuman/{pengumuman}', [AdminPengumumanController::class, 'destroy'])->name('pengumuman.destroy');
            Route::post('/pengumuman/upload-image', [AdminPengumumanController::class, 'uploadImage'])->name('pengumuman.upload-image');

            Route::get('/arsip', [AdminArsipController::class, 'index'])->name('arsip.index');

            // Master data
            Route::get('/fakultas', [AdminFakultasController::class, 'index'])->name('fakultas.index');
            Route::get('/fakultas/create', [AdminFakultasController::class, 'create'])->name('fakultas.create');
            Route::post('/fakultas', [AdminFakultasController::class, 'store'])->name('fakultas.store');
            Route::get('/fakultas/{fakultas}/edit', [AdminFakultasController::class, 'edit'])->name('fakultas.edit');
            Route::put('/fakultas/{fakultas}', [AdminFakultasController::class, 'update'])->name('fakultas.update');
            Route::delete('/fakultas/{fakultas}', [AdminFakultasController::class, 'destroy'])->name('fakultas.destroy');

            Route::get('/prodi', [AdminProdiController::class, 'index'])->name('prodi.index');
            Route::get('/prodi/create', [AdminProdiController::class, 'create'])->name('prodi.create');
            Route::post('/prodi', [AdminProdiController::class, 'store'])->name('prodi.store');
            Route::get('/prodi/{prodi}/edit', [AdminProdiController::class, 'edit'])->name('prodi.edit');
            Route::put('/prodi/{prodi}', [AdminProdiController::class, 'update'])->name('prodi.update');
            Route::delete('/prodi/{prodi}', [AdminProdiController::class, 'destroy'])->name('prodi.destroy');

            // Security (2FA devices)
            Route::get('/security', [AdminSecurityController::class, 'index'])->name('security.index');
            Route::post('/security/devices', [AdminSecurityController::class, 'addDevice'])->name('security.devices.store');
            Route::delete('/security/devices/{device}', [AdminSecurityController::class, 'removeDevice'])->name('security.devices.destroy');
        });
    });
