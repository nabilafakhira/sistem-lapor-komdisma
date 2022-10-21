<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\ChartSvController;
use App\Http\Controllers\RekapanController;
use App\Http\Controllers\AkademikController;
use App\Http\Controllers\KomdismaController;
use App\Http\Controllers\SkorsingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ChartProdiController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\MahasiswaJadwalController;
use App\Http\Controllers\JenisPelanggaranController;
use App\Http\Controllers\LaporPelanggaranController;
use App\Http\Controllers\LokasiPelanggaranController;
use App\Http\Controllers\MahasiswaSkorsingController;
use App\Http\Controllers\PenundaanSkorsingController;
use App\Http\Controllers\SuratKelakuanBaikController;
use App\Http\Controllers\TambahPelanggaranController;
use App\Http\Controllers\KategoriPelanggaranController;
use App\Http\Controllers\MahasiswaPelanggaranController;
use App\Http\Controllers\VerifikasiPelanggaranController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Dashboard
Route::get('/', [DashboardController::class, 'index'])->middleware('login')->name('dashboard');

//Auth
Route::get('/pengurus/login', [AuthController::class, 'loginPengurus'])->name('login.pengurus');
Route::post('/pengurus/login', [AuthController::class, 'postLoginPengurus'])->name('post.login.pengurus');
Route::get('/login', [AuthController::class, 'loginMahasiswa'])->name('login.mahasiswa');
Route::post('/login', [AuthController::class, 'postLoginMahasiswa'])->name('post.login.mahasiswa');
Route::get('/pengurus/logout', [AuthController::class, 'logoutPengurus'])->name('logout.pengurus');
Route::get('/mahasiswa/logout', [AuthController::class, 'logoutMahasiswa'])->name('logout.mahasiswa');

//Role: Super Admin
Route::middleware(['login', 'role:super-admin'])->group(function () {
    Route::post('/admin/akun/hapus', [AkunController::class, 'hapusAkun'])->name('delete.akun');
    Route::get('/admin/komdisma', [KomdismaController::class, 'show'])->name('show.komdisma');
    Route::post('/admin/komdisma/simpan', [KomdismaController::class, 'store'])->name('store.komdisma');
    Route::post('/admin/komdisma/import', [KomdismaController::class, 'import'])->name('import.komdisma');
    Route::get('/komdisma/role/super-admin', [AkunController::class, 'makeSuperAdmin'])->name('make.super.admin');
    Route::get('/komdisma/role/admin', [AkunController::class, 'makeAdmin'])->name('make.admin');
    Route::get('/komdisma/role/dosen', [AkunController::class, 'makeDosen'])->name('make.dosen');
});

//Role: Super Admin, Admin
Route::middleware(['login', 'role:super-admin,admin'])->group(function () {
    Route::get('/admin/verifikasi', [VerifikasiPelanggaranController::class, 'show'])->name('show.verifikasi');
    Route::any('/admin/verifikasi/ajax', [VerifikasiPelanggaranController::class, 'getAllAjax'])->name('ajax.verifikasi');
    Route::any('/admin/verifikasi/detail', [VerifikasiPelanggaranController::class, 'showDetail'])->name('detail.verifikasi');
    Route::post('/admin/verifikasi/update', [VerifikasiPelanggaranController::class, 'update'])->name('update.verifikasi');
    Route::post('/admin/verifikasi/edit', [VerifikasiPelanggaranController::class, 'updateNew'])->name('update.new.verifikasi');
    Route::any('/admin/pelanggaran/detail', [PelanggaranController::class, 'showDetail'])->name('detail.pelanggaran');
    Route::get('/admin/pelanggaran/loloskan/{id}', [PelanggaranController::class, 'loloskan'])->name('loloskan.pelanggaran');
    Route::get('/akun/reset', [AkunController::class, 'resetPasswordPengurus'])->name('reset.akun.pengurus');
    Route::get('/mahasiswa/akun/reset', [AkunController::class, 'resetPasswordMahasiswa'])->name('reset.akun.mahasiswa');

    //Master Data
    Route::get('/admin/dosen', [DosenController::class, 'show'])->name('show.dosen');
    Route::post('/admin/dosen/simpan', [DosenController::class, 'store'])->name('store.dosen');
    Route::post('/admin/dosen/import', [DosenController::class, 'import'])->name('import.dosen');
    Route::any('/admin/dosen/ajax', [DosenController::class, 'getAllAjax'])->name('ajax.dosen');
    Route::get('/admin/akademik', [AkademikController::class, 'show'])->name('show.akademik');
    Route::post('/admin/akademik/simpan', [AkademikController::class, 'store'])->name('store.akademik');
    Route::post('/admin/akademik/import', [AkademikController::class, 'import'])->name('import.akademik');
    Route::get('/admin/mahasiswa', [MahasiswaController::class, 'show'])->name('show.mahasiswa');
    Route::post('/admin/mahasiswa/simpan', [MahasiswaController::class, 'store'])->name('store.mahasiswa');
    Route::post('/admin/mahasiswa/import', [MahasiswaController::class, 'import'])->name('import.mahasiswa');
    Route::any('/admin/mahasiswa/ajax', [MahasiswaController::class, 'getAllAjax'])->name('ajax.mahasiswa');
    Route::get('/admin/prodi', [ProdiController::class, 'show'])->name('show.prodi');
    Route::post('/admin/prodi/simpan', [ProdiController::class, 'store'])->name('store.prodi');
    Route::post('/admin/prodi/edit', [ProdiController::class, 'update'])->name('update.prodi');
    Route::get('/pelanggaran/jenis', [JenisPelanggaranController::class, 'show'])->name('show.jenis.pelanggaran');
    Route::post('/pelanggaran/jenis/simpan', [JenisPelanggaranController::class, 'store'])->name('store.jenis.pelanggaran');
    Route::post('/pelanggaran/jenis/edit', [JenisPelanggaranController::class, 'update'])->name('update.jenis.pelanggaran');
    Route::get('/pelanggaran/kategori/ajax', [KategoriPelanggaranController::class, 'getAjax'])->name('ajax.kategori.pelanggaran');
    Route::get('/pelanggaran/lokasi', [LokasiPelanggaranController::class, 'show'])->name('show.lokasi.pelanggaran');
    Route::post('/pelanggaran/lokasi/simpan', [LokasiPelanggaranController::class, 'store'])->name('store.lokasi.pelanggaran');
    Route::post('/pelanggaran/lokasi/edit', [LokasiPelanggaranController::class, 'update'])->name('update.lokasi.pelanggaran');
    //End Master Data
    
    Route::get('/admin/akun', [AkunController::class, 'show'])->name('show.akun');
    Route::any('/admin/akun/ajax', [AkunController::class, 'getAllAjax'])->name('ajax.akun');
    Route::get('/admin/skorsing/penundaan', [PenundaanSkorsingController::class, 'show'])->name('show.penundaan.skorsing');
    Route::any('/admin/skorsing/penundaan/detail', [PenundaanSkorsingController::class, 'showDetail'])->name('detail.penundaan.skorsing');
    Route::post('/admin/skorsing/penundaan/terima', [PenundaanSkorsingController::class, 'terimaPenundaan'])->name('terima.penundaan.skorsing');
    Route::post('/admin/skorsing/penundaan/tolak', [PenundaanSkorsingController::class, 'tolakPenundaan'])->name('tolak.penundaan.skorsing');
    Route::get('/admin/surat-kelakuan-baik', [SuratKelakuanBaikController::class, 'show'])->name('show.surat.kelakuan.baik');
    Route::post('/admin/surat-kelakuan-baik/terima', [SuratKelakuanBaikController::class, 'terimaPengajuan'])->name('terima.surat.kelakuan.baik');
    Route::post('/admin/surat-kelakuan-baik/tolak', [SuratKelakuanBaikController::class, 'tolakPengajuan'])->name('tolak.surat.kelakuan.baik');
});

//Role: Super Admin, Admin, Dosen
Route::middleware(['login', 'role:super-admin,admin,dosen'])->group(function () {
    Route::any('/pelanggaran/tambah', [TambahPelanggaranController::class, 'show'])->name('add.pelanggaran');
    Route::post('/pelanggaran/simpan', [TambahPelanggaranController::class, 'store'])->name('save.pelanggaran');
    Route::post('/pelanggaran/form/validation', [TambahPelanggaranController::class, 'validation'])->name('validation.form.pelanggaran');
    Route::get('/count/all', [PelanggaranController::class, 'getCountAjax'])->name('ajax.count.notif');
    Route::any("/data/jenis-pelanggaran", [JenisPelanggaranController::class, 'getAllAjax'])->name('json.jenispel');
    Route::any("/laporan", [LaporPelanggaranController::class, 'show'])->name('show.laporan');
    Route::any("/laporan/ajax", [LaporPelanggaranController::class, 'getAllAjax'])->name('ajax.laporan');
    Route::post("/laporan/terima", [LaporPelanggaranController::class, 'update'])->name('acc.laporan');
});

//Role: Super Admin, Admin, Akademik
Route::middleware(['login', 'role:super-admin,admin,akademik'])->group(function () {
    Route::get('/admin/pelanggaran', [PelanggaranController::class, 'show'])->name('show.pelanggaran');
    Route::get('/admin/skorsing', [SkorsingController::class, 'show'])->name('show.skorsing');
    Route::any('/admin/skorsing/detail', [SkorsingController::class, 'showDetail'])->name('detail.skorsing');
    Route::get('/admin/rekapan', [RekapanController::class, 'show'])->name('show.rekapan');
});

//Role: Super Admin, Admin, Dosen, Akademik
Route::middleware(['login', 'role:super-admin,admin,dosen,akademik'])->group(function () {
    Route::get('/akun/pengurus', [AkunController::class, 'editPengurus'])->name('show.edit.akun.pengurus');
    Route::post('/akun/pengurus/validation', [AkunController::class, 'validationPengurus'])->name('validation.edit.akun.pengurus');
    Route::post('/akun/pengurus/edit', [AkunController::class, 'updatePengurus'])->name('update.akun.pengurus');
    Route::get('/getDataChartJSON', [PelanggaranController::class, 'chartJSON'])->name('chart.json');
    Route::get('/grafik/sv', [ChartSvController::class, 'show'])->name('show.chart.sv');
    Route::get('getMonthlyChart', [ChartSvController::class, 'monthlyChart'])->name('getMonthlyChart');
    Route::any('getChartByKategori', [ChartSvController::class, 'chartByKategori'])->name('getChartByKategori');
    Route::any('getChartByLokasi', [ChartSvController::class, 'chartByLokasi'])->name('getChartByLokasi');
    Route::any('getChartBySanksi', [ChartSvController::class, 'chartBySanksi'])->name('getChartBySanksi');
    Route::get('/grafik/prodi', [ChartProdiController::class, 'show'])->name('show.chart.prodi');
    Route::any('getMonthlyChartProdi', [ChartProdiController::class, 'monthlyChartProdi'])->name('getMonthlyChartProdi');
    Route::any('getChartByKategoriProdi', [ChartProdiController::class, 'chartByKategoriProdi'])->name('getChartByKategoriProdi');
    Route::any('getChartByLokasiProdi', [ChartProdiController::class, 'chartByLokasiProdi'])->name('getChartByLokasiProdi');
    Route::any('getChartBySanksiProdi', [ChartProdiController::class, 'chartBySanksiProdi'])->name('getChartBySanksiProdi');

});


//Role: Mahasiswa
Route::middleware(['login', 'role:mahasiswa'])->group(function () {
    Route::get('/mahasiswa/pelanggaran', [MahasiswaPelanggaranController::class, 'show'])->name('mahasiswa.show.pelanggaran');
    Route::any('/mahasiswa/pelanggaran/detail', [MahasiswaPelanggaranController::class, 'showDetail'])->name('mahasiswa.detail.pelanggaran');
    Route::post('/mahasiswa/lapor', [MahasiswaPelanggaranController::class, 'storeLapor'])->name('mahasiswa.lapor');
    Route::any('/mahasiswa/jadwal/tambah', [MahasiswaJadwalController::class, 'add'])->name('mahasiswa.add.jadwal');    
    Route::post('/mahasiswa/jadwal/simpan', [MahasiswaJadwalController::class, 'store'])->name('mahasiswa.store.jadwal');
    Route::get('/akun/mahasiswa', [AkunController::class, 'editMahasiswa'])->name('show.edit.akun.mahasiswa');
    Route::post('/akun/mahasiswa/validation', [AkunController::class, 'validationMahasiswa'])->name('validation.edit.akun.mahasiswa');
    Route::post('/akun/mahasiswa/edit', [AkunController::class, 'updateMahasiswa'])->name('update.akun.mahasiswa');    
    Route::any('/mahasiswa/skorsing/penundaan', [MahasiswaSkorsingController::class, 'addPenundaan'])->name('form.penundaan.skorsing');    
    Route::post('/mahasiswa/skorsing/penundaan/simpan', [MahasiswaSkorsingController::class, 'storePenundaan'])->name('add.penundaan.skorsing');    
    Route::get('/surat-kelakuan-baik', [SuratKelakuanBaikController::class, 'add'])->name('add.surat.kelakuan.baik');    
    Route::post('/surat-kelakuan-baik/simpan', [SuratKelakuanBaikController::class, 'store'])->name('store.surat.kelakuan.baik');    
    Route::post('/surat-kelakuan-baik/unduh', [SuratKelakuanBaikController::class, 'unduhSurat'])->name('unduh.surat.kelakuan.baik');    
    Route::post('/surat-bebas-lapor/unduh', [MahasiswaPelanggaranController::class, 'unduhSurat'])->name('unduh.surat.bebas');    
    Route::get('/mahasiswa/getDataChartJSON', [MahasiswaPelanggaranController::class, 'chartJSON'])->name('mahasiswa.chart.json');
});
