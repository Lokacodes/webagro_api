<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BotController;
use App\Http\Controllers\CFPenggunaController;
use App\Http\Controllers\DashboardKeuanganController;
use App\Http\Controllers\DiagnosaController;
use App\Http\Controllers\GejalaController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\JenisTanamanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KontrolController;
use App\Http\Controllers\KontrolLingkunganController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PendapatanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PengetahuanController;
use App\Http\Controllers\PenyakitController;
use App\Http\Controllers\PerangkatController;
use App\Http\Controllers\PompaController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PupukController;
use App\Http\Controllers\RekapAbsenController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\RiwayatPenyakitController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\SDMController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\SOPController;
use App\Http\Controllers\TanamanController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\GreenhouseController;
use App\Http\Controllers\PanenController;
use App\Http\Libraries\System;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Login
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api_user');

// IOT
Route::post('/sensor', [SensorController::class, 'store']);
Route::get('/pompa/{perangkat_id}', [KontrolController::class, 'status']);

// Greenhouse Absen
Route::get('/greenhouse-absen', [GreenhouseController::class, 'findAll']);
// Absensi
Route::get('/absensi-status/{id}', [AbsensiController::class, 'findOneAbsensi']);
Route::get('/absensi/{id}', [AbsensiController::class, 'findAbsensi']);
// Jabatan
Route::get('/jabatan', [JabatanController::class, 'findAll']);
// karyawan User
Route::get('/karyawan-user/{id}', [KaryawanController::class, 'findOneUser']);

// create Absensi
Route::post('/absensi', [AbsensiController::class, 'store']);
// sop karyawan
Route::get('/sop-karyawan/{id}', [SOPController::class, 'findSOPKaryawan']);
// Users
Route::get('/users', [UsersController::class, 'findAll']);

// Bot Webhook
Route::post('/bot/webhook', [BotController::class, 'callback']);

Route::prefix('/absen')->middleware('api_absen')->group(
    function () {
        // User
        Route::get('/users', [UsersController::class, 'findAll']);
        Route::get('/users/{id}', [UsersController::class, 'findOne']);
        Route::post('/users', [UsersController::class, 'store']);
        Route::put('/users/{id}', [UsersController::class, 'update']);
        Route::put('/users/reset/{id}', [UsersController::class, 'resetPassword']);
        Route::delete('/users/{id}', [UsersController::class, 'destroy']);

        // Group
        Route::get('/group', [GroupController::class, 'findAll']);

        // Role
        Route::get('/role/{id}', [RoleController::class, 'findOne']);

        // Jabatan
        Route::get('/jabatan', [JabatanController::class, 'findAll']);

        // Karyawan
        Route::get('/karyawan', [KaryawanController::class, 'findAll']);
        Route::get('/karyawan/{id}', [KaryawanController::class, 'findOne']);
        Route::get('/karyawan-user/{id}', [KaryawanController::class, 'findOneUser']);
        Route::get('/karyawan-export', [KaryawanController::class, 'exportExcel']);
        Route::post('/karyawan', [KaryawanController::class, 'store']);
        Route::post('/karyawan/{id}', [KaryawanController::class, 'edit']);
        Route::delete('/karyawan/{id}', [KaryawanController::class, 'destroy']);

        // SOP
        Route::get('/sop', [SOPController::class, 'findAll']);
        Route::get('/sop/{id}', [SOPController::class, 'findOne']);
        Route::get('/sop-export', [SOPController::class, 'exportExcel']);
        Route::post('/sop', [SOPController::class, 'store']);
        Route::put('/sop/{id}', [SOPController::class, 'update']);
        Route::delete('/sop/{id}', [SOPController::class, 'destroy']);

        // Absensi
        Route::get('/absensi-status/{id}', [AbsensiController::class, 'findOneAbsensi']);
        Route::get('/absensi/{id}', [AbsensiController::class, 'findAbsensi']);
        Route::get('/rekap-absensi-export', [AbsensiController::class, 'exportExcel']);
        Route::get('/absensi-grafik', [AbsensiController::class, 'findToGrafik']);
        Route::get('/absensi', [AbsensiController::class, 'findAll']);
        Route::post('/absensi', [AbsensiController::class, 'store']);
        Route::put('/absensi/{id}', [AbsensiController::class, 'update']);
        Route::delete('/absensi/{id}', [AbsensiController::class, 'destroy']);

        // Rekap Absen
        Route::get('/rekap-absensi', [RekapAbsenController::class, 'findAll']);
        Route::get('/rekap-absensi-karyawan/{id}', [RekapAbsenController::class, 'findOneKaryawan']);
        Route::get('/rekap-absensi-panen/{id}', [RekapAbsenController::class, 'findAllPanen']);
        Route::post('/rekap-absensi/{id}', [RekapAbsenController::class, 'rekapPanen']);
        Route::delete('/rekap-absensi-delete/{id}', [RekapAbsenController::class, 'destroy']);


        // panen
        Route::get('/panen-absen', [PanenController::class, 'findAbsenAll']);
        Route::get('/panen', [PanenController::class, 'findAll']);
        // Jenis
        Route::get('/jenis', [JenisController::class, 'findAll']);

        // Jenis Tanaman
        Route::get('/jenis-tanaman', [JenisTanamanController::class, 'findAll']);

        // Satuan
        Route::get('/satuan', [SatuanController::class, 'findAll']);

        // Pupuk
        Route::get('/pupuk', [PupukController::class, 'findAll']);
        Route::get('/pupuk/{id}', [PupukController::class, 'findOne']);
        Route::get('/pupuk-export', [PupukController::class, 'exportExcel']);
        Route::post('/pupuk', [PupukController::class, 'store']);
        Route::put('/pupuk/{id}', [PupukController::class, 'update']);
        Route::delete('/pupuk/{id}', [PupukController::class, 'destroy']);

        // Tanaman
        Route::get('/tanaman', [TanamanController::class, 'findAll']);
        Route::get('/tanaman-export', [TanamanController::class, 'exportExcel']);
        Route::get('/tanaman/{id}', [TanamanController::class, 'findOne']);
        Route::post('/tanaman', [TanamanController::class, 'store']);
        Route::put('/tanaman/{id}', [TanamanController::class, 'update']);
        Route::delete('/tanaman/{id}', [TanamanController::class, 'destroy']);

        // Kontrol Lingkungan
        Route::get('/kontrol-lingkungan', [KontrolLingkunganController::class, 'findAll']);
        Route::get('/kontrol-lingkungan/{id}', [KontrolLingkunganController::class, 'findOne']);
        Route::get('/kontrol-lingkungan-export', [KontrolLingkunganController::class, 'exportExcel']);
        Route::post('/kontrol-lingkungan', [KontrolLingkunganController::class, 'store']);
        Route::put('/kontrol-lingkungan/{id}', [KontrolLingkunganController::class, 'update']);
        Route::delete('/kontrol-lingkungan/{id}', [KontrolLingkunganController::class, 'destroy']);

        // Greenhouse Lat Long
        Route::put('/greenhouse-lat-long/{id}', [GreenhouseController::class, 'updateLatLong']);

        // Greenhouse Absen
        Route::get('/greenhouse-absen', [GreenhouseController::class, 'findAll']);

        // sensor
        Route::get('/sensor', [SensorController::class, 'findAll']);

        // dashboard Keuangan
        Route::get('/dashboard/{gh_id}/chart', [DashboardKeuanganController::class, 'getDataChart']);
        Route::get('/dashboard/{gh_id}/pie', [DashboardKeuanganController::class, 'getDataPie']);
        Route::get('/dashboard/{gh_id}/laba/{tahun}', [DashboardKeuanganController::class, 'getDataLaba']);
        Route::get('/dashboard/{gh_id}/panen', [DashboardKeuanganController::class, 'getPanenByGreenhouse']);
        Route::get('/dashboard/{gh_id}/total', [DashboardKeuanganController::class, 'getTotal']);

        // dashboard Penyakit

        Route::get('/riwayat-penyakit-greenhouse', [RiwayatPenyakitController::class, 'penyakitGreenhouse']);
        Route::get('/post', [PostController::class, 'findAll']);
        Route::get('/pengetahuan', [PengetahuanController::class, 'findAll']);
        Route::get('/gejala', [GejalaController::class, 'findAll']);
        Route::get('/penyakit', [PenyakitController::class, 'findAll']);

        // dashboard IOT

        Route::delete('/notifikasi/{id}/all', [NotifikasiController::class, 'destroyAll']);
        Route::get('/notifikasi/{gh_id}/dashboard', [NotifikasiController::class, 'getNotifikasiDashboard']);
        Route::get('/sensor/{gh_id}/lastest', [SensorController::class, 'findDataSensorTerbaru']);
    }
);


Route::prefix('/iot')->middleware('auth:api_user')->group(function () {

    // perangkat
    Route::get('/perangkat', [PerangkatController::class, 'findAll']);
    Route::get('/perangkat/{id}', [PerangkatController::class, 'findOne']);
    Route::get('/perangkat-greenhouse/{id}', [PerangkatController::class, 'findOneGreenhouse']);
    Route::post('/perangkat', [PerangkatController::class, 'store']);
    Route::put('/perangkat/{id}', [PerangkatController::class, 'update']);
    Route::delete('/perangkat/{id}', [PerangkatController::class, 'destroy']);

    // sensor
    Route::get('/sensor', [SensorController::class, 'findAll']);
    Route::get('/sensor/{gh_id}/lastest', [SensorController::class, 'findDataSensorTerbaru']);
    Route::get('/sensor/{gh_id}/chart', [SensorController::class, 'getChartData']);

    // perangkat
    Route::get('/notifikasi', [NotifikasiController::class, 'findAll']);
    Route::get('/notifikasi/{gh_id}/dashboard', [NotifikasiController::class, 'getNotifikasiDashboard']);
    Route::post('/notifikasi', [NotifikasiController::class, 'store']);
    Route::put('/notifikasi/{id}', [NotifikasiController::class, 'update']);
    Route::delete('/notifikasi/{id}', [NotifikasiController::class, 'destroy']);
    Route::delete('/notifikasi/{id}/all', [NotifikasiController::class, 'destroyAll']);

    // pompa
    Route::get('/pompa/{id}', [PompaController::class, 'findOne']);
    Route::post('/pompa', [PompaController::class, 'store']);
    Route::put('/pompa/{id}', [PompaController::class, 'update']);
    Route::delete('/pompa/{id}', [PompaController::class, 'destroy']);

    // greenhouse
    Route::get('/greenhouse', [GreenhouseController::class, 'findAll']);
    Route::get('/greenhouse/{id}', [GreenhouseController::class, 'findOne']);
    Route::post('/greenhouse', [GreenhouseController::class, 'store']);
    Route::post('/greenhouse/{id}', [GreenhouseController::class, 'update']);
    Route::delete('/greenhouse/{id}', [GreenhouseController::class, 'destroy']);

    // Kontrol
    Route::get('/kontrol', [KontrolController::class, 'findAll']);
    Route::get('/kontrol/{id}', [KontrolController::class, 'findOneWherePerangkat']);
    Route::post('/kontrol', [KontrolController::class, 'store']);
    Route::put('/kontrol/{id}', [KontrolController::class, 'update']);
    Route::delete('/kontrol/{id}', [KontrolController::class, 'destroy']);


    // Jenis Tanaman
    Route::get('/jenis-tanaman', [JenisTanamanController::class, 'findAll']);

    Route::post('/jenis-tanaman', [JenisTanamanController::class, 'store']);
});

Route::prefix('/keuangan')->middleware('api_keuangan')->group(function () {
    // panen
    Route::get('/panen', [PanenController::class, 'findAll']);
    Route::get('/panen-last', [PanenController::class, 'findOneLast']);
    Route::get('/panen/{id}', [PanenController::class, 'findOne']);
    Route::get('/panen-export', [PanenController::class, 'exportExcel']);
    Route::post('/panen', [PanenController::class, 'store']);
    Route::put('/panen/{id}', [PanenController::class, 'update']);
    Route::delete('/panen/{id}', [PanenController::class, 'destroy']);
    Route::get('/tahun-panen', [PanenController::class, 'getTahunPanen']);

    // sdm
    Route::get('/sdm', [SDMController::class, 'findAll']);
    Route::get('/sdm/{id}', [SDMController::class, 'findOne']);
    Route::get('/sdm-export', [SDMController::class, 'exportExcel']);
    Route::post('/sdm', [SDMController::class, 'store']);
    Route::put('/sdm/{id}', [SDMController::class, 'update']);
    Route::delete('/sdm/{id}', [SDMController::class, 'destroy']);

    // Pengeluaran
    Route::get('/pengeluaran', [PengeluaranController::class, 'findAll']);
    Route::get('/pengeluaran/{id}', [PengeluaranController::class, 'findOne']);
    Route::get('/pengeluaran-export', [PengeluaranController::class, 'exportExcel']);
    Route::post('/pengeluaran', [PengeluaranController::class, 'store']);
    Route::put('/pengeluaran/{id}', [PengeluaranController::class, 'update']);
    Route::delete('/pengeluaran/{id}', [PengeluaranController::class, 'destroy']);

    // Pendapatan
    Route::get('/pendapatan', [PendapatanController::class, 'findAll']);
    Route::get('/pendapatan/{id}', [PendapatanController::class, 'findOne']);
    Route::get('/pendapatan-export', [PendapatanController::class, 'exportExcel']);
    Route::post('/pendapatan', [PendapatanController::class, 'store']);
    Route::put('/pendapatan/{id}', [PendapatanController::class, 'update']);
    Route::delete('/pendapatan/{id}', [PendapatanController::class, 'destroy']);

    // Modal
    Route::get('/modal', [ModalController::class, 'findAll']);
    Route::get('/modal/{id}', [ModalController::class, 'findOne']);
    Route::get('/modal-export', [ModalController::class, 'exportExcel']);
    Route::post('/modal', [ModalController::class, 'store']);
    Route::put('/modal/{id}', [ModalController::class, 'update']);
    Route::delete('/modal/{id}', [ModalController::class, 'destroy']);

    // absensi
    Route::get('/absensi', [AbsensiController::class, 'findAll']);
    // karyawan
    Route::get('/karyawan', [KaryawanController::class, 'findAll']);
    // User
    Route::get('/users', [UsersController::class, 'findAll']);
    // Satuan
    Route::get('/satuan', [SatuanController::class, 'findAll']);
    // greenhouse
    Route::get('/greenhouse', [GreenhouseController::class, 'findAll']);

    // dashboard
    Route::get('/dashboard/{gh_id}/chart', [DashboardKeuanganController::class, 'getDataChart']);
    Route::get('/dashboard/{gh_id}/pie', [DashboardKeuanganController::class, 'getDataPie']);
    Route::get('/dashboard/{gh_id}/laba/{tahun}', [DashboardKeuanganController::class, 'getDataLaba']);
    Route::get('/dashboard/{gh_id}/panen', [DashboardKeuanganController::class, 'getPanenByGreenhouse']);
    Route::get('/dashboard/{gh_id}/total', [DashboardKeuanganController::class, 'getTotal']);
});

Route::prefix('/penyakit')->middleware('api_penyakit')->group(function () {
    // penyakit
    Route::get('/penyakit', [PenyakitController::class, 'findAll']);
    Route::get('/penyakit-riwayat/{id}', [PenyakitController::class, 'findPenyakitRiwayat']);
    Route::get('/penyakit/{id}', [PenyakitController::class, 'findOne']);
    Route::post('/penyakit', [PenyakitController::class, 'store']);
    Route::put('/penyakit/{id}', [PenyakitController::class, 'update']);
    Route::delete('/penyakit/{id}', [PenyakitController::class, 'destroy']);

    // gejala
    Route::get('/gejala', [GejalaController::class, 'findAll']);
    Route::get('/gejala/{id}', [GejalaController::class, 'findOne']);
    Route::post('/gejala', [GejalaController::class, 'store']);
    Route::put('/gejala/{id}', [GejalaController::class, 'update']);
    Route::delete('/gejala/{id}', [GejalaController::class, 'destroy']);

    // pengetahuan
    Route::get('/pengetahuan', [PengetahuanController::class, 'findAll']);
    Route::get('/pengetahuan/{id}', [PengetahuanController::class, 'findOne']);
    Route::post('/pengetahuan', [PengetahuanController::class, 'store']);
    Route::put('/pengetahuan/{id}', [PengetahuanController::class, 'update']);
    Route::delete('/pengetahuan/{id}', [PengetahuanController::class, 'destroy']);

    // CF pengguna
    Route::get('/cf', [CFPenggunaController::class, 'findAll']);
    Route::post('/cf', [CFPenggunaController::class, 'store']);
    Route::put('/cf/{id}', [CFPenggunaController::class, 'update']);
    Route::delete('/cf/{id}', [CFPenggunaController::class, 'destroy']);

    // Diagnosa
    Route::get('/diagnosa', [DiagnosaController::class, 'findAll']);
    Route::get('/diagnosa/{id}', [DiagnosaController::class, 'findOne']);
    Route::get('/diagnosa-greenhouse/{id}', [DiagnosaController::class, 'findAllGreenhouse']);
    Route::get('/riwayat-konsultasi-export', [DiagnosaController::class, 'exportExcelKonsultasi']);
    Route::get('/data-diagnosa-export', [DiagnosaController::class, 'exportExcel']);
    Route::post('/diagnosa', [DiagnosaController::class, 'store']);
    Route::post('/diagnosa/{id}', [DiagnosaController::class, 'update']);
    Route::delete('/diagnosa/{id}', [DiagnosaController::class, 'destroy']);

    // Riwayat
    Route::get('/riwayat', [RiwayatController::class, 'findAll']);
    Route::get('/riwayat/{id}', [RiwayatController::class, 'findOne']);
    Route::get('/riwayat-diagnosa/{id}', [RiwayatController::class, 'findRiwayatDiagnosa']);
    Route::get('/riwayat-diagnosa-update/{id}', [RiwayatController::class, 'findRiwayatDiagnosaUpdate']);
    Route::post('/riwayat', [RiwayatController::class, 'store']);
    Route::put('/riwayat/{id}', [RiwayatController::class, 'update']);
    Route::delete('/riwayat/{id}', [RiwayatController::class, 'destroy']);

    // Riwayat Penyakit
    Route::get('/riwayat-penyakit', [RiwayatPenyakitController::class, 'findAll']);
    Route::get('/riwayat-penyakit-greenhouse', [RiwayatPenyakitController::class, 'penyakitGreenhouse']);
    Route::get('/riwayat-penyakit/{id}', [RiwayatPenyakitController::class, 'findAllById']);
    Route::post('/riwayat-penyakit/{id}', [RiwayatPenyakitController::class, 'store']);
    // Route::put('/riwayat-penyakit/{id}', [RiwayatPenyakitController::class, 'update']);
    Route::delete('/riwayat-penyakit/{id}', [RiwayatPenyakitController::class, 'destroy']);
    Route::get('/statistik-penyakit', [RiwayatPenyakitController::class, 'getPenyakitStatistics']);

    // Post
    Route::get('/post', [PostController::class, 'findAll']);
    Route::get('/post/{id}', [PostController::class, 'findOne']);
    Route::post('/post', [PostController::class, 'store']);
    Route::post('/post/{id}', [PostController::class, 'update']);
    Route::delete('/post/{id}', [PostController::class, 'destroy']);

    // PengetahuanDiagnosaArray
    Route::post('/pengetahuan-gejala-array', [PengetahuanController::class, 'getPengetahuanGejalaArray']);

    // greenhouse
    Route::get('/greenhouse', [GreenhouseController::class, 'findAll']);

    // panen Greenhouse
    Route::get('/panen-greenhouse/{id}', [PanenController::class, 'findPanenGreenhouse']);

    // Tanaman Greenhouse
    Route::get('/tanaman-greenhouse/{id}', [TanamanController::class, 'findTanamanGreenhouse']);
});


// 404 Not Found
Route::any('{any}', function () {
    return System::response(404, [
        'statusCode' => 404,
        'message' => 'Page not found'
    ]);
});
