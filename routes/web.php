<?php

use App\Http\Controllers\{
    DashboardController,
    KategoriController,
    LaporanController,
    ProdukController,
    PengeluaranController,
    PenjualanController,
    PenjualanDetailController,
    SettingController,
    UserController,
    FinancialReportController,
    AttendanceController,
};
use App\Models\Setting;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->route('login');
});

// Dynamic PWA manifest using app settings
Route::get('/manifest.json', function () {
    $setting = Setting::first();
    $manifest = [
        'name' => $setting->nama_perusahaan ?? config('app.name'),
        'short_name' => $setting->nama_perusahaan ?? config('app.name'),
        'start_url' => url('/'),
        'scope' => url('/'),
        'display' => 'standalone',
        'background_color' => '#ffffff',
        'theme_color' => '#1976D2',
    ];

    // Try to embed the configured logo as base64 data-URL icons so manifest
    // remains valid even if separate icon files aren't present on disk.
    $icons = [];
    $logo = $setting->path_logo ?? null;
    if ($logo) {
        $logoPath = public_path(ltrim($logo, '/'));
        if (file_exists($logoPath)) {
            $mime = @mime_content_type($logoPath) ?: 'image/png';
            $data = base64_encode(file_get_contents($logoPath));
            $dataUrl = 'data:'.$mime.';base64,'.$data;

            $icons[] = [
                'src' => $dataUrl,
                'sizes' => '192x192',
                'type' => $mime,
                'purpose' => 'any maskable',
            ];
            $icons[] = [
                'src' => $dataUrl,
                'sizes' => '512x512',
                'type' => $mime,
                'purpose' => 'any maskable',
            ];
        } else {
            // Fallback to public URL if file not available on the filesystem
            $icons[] = [
                'src' => url($logo),
                'sizes' => '192x192',
                'type' => 'image/png',
                'purpose' => 'any maskable',
            ];
            $icons[] = [
                'src' => url($logo),
                'sizes' => '512x512',
                'type' => 'image/png',
                'purpose' => 'any maskable',
            ];
        }
    }

    // Final fallback to expected icon paths
    if (empty($icons)) {
        $icons[] = [
            'src' => url('/img/icon-192x192.png'),
            'sizes' => '192x192',
            'type' => 'image/png',
            'purpose' => 'any maskable',
        ];
        $icons[] = [
            'src' => url('/img/icon-512x512.png'),
            'sizes' => '512x512',
            'type' => 'image/png',
            'purpose' => 'any maskable',
        ];
    }

    $manifest['icons'] = $icons;

    return response()->json($manifest);
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
        Route::resource('/kategori', KategoriController::class);

        Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
        Route::post('/produk/delete-selected', [ProdukController::class, 'deleteSelected'])->name('produk.delete_selected');
        Route::post('/produk/cetak-barcode', [ProdukController::class, 'cetakBarcode'])->name('produk.cetak_barcode');
        Route::resource('/produk', ProdukController::class);



        Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
        Route::resource('/pengeluaran', PengeluaranController::class);

        Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
        Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
        Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
    });

    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.baru');
        Route::post('/transaksi/simpan', [PenjualanController::class, 'store'])->name('transaksi.simpan');
        Route::get('/transaksi/selesai', [PenjualanController::class, 'selesai'])->name('transaksi.selesai');
        Route::get('/transaksi/nota-kecil', [PenjualanController::class, 'notaKecil'])->name('transaksi.nota_kecil');
        Route::get('/transaksi/nota-besar', [PenjualanController::class, 'notaBesar'])->name('transaksi.nota_besar');

        Route::get('/transaksi/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
        Route::get('/transaksi/loadform/{diskon}/{total}/{diterima}', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.load_form');
        Route::resource('/transaksi', PenjualanDetailController::class)
            ->except('create', 'show', 'edit');

        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/clock-out/{id}', [AttendanceController::class, 'clockOut'])->name('attendance.clock_out');
    });

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
        Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');

        Route::get('/accounting/reports', [FinancialReportController::class, 'index'])->name('accounting.reports.index');
        Route::get('/accounting/balance-sheet', [FinancialReportController::class, 'showBalanceSheet'])->name('accounting.balance_sheet');
        Route::get('/accounting/income-statement', [FinancialReportController::class, 'showIncomeStatement'])->name('accounting.income_statement');
        Route::get('/accounting/changes-in-equity', [FinancialReportController::class, 'showChangesInEquity'])->name('accounting.changes_in_equity');
        Route::get('/accounting/cash-flow', [FinancialReportController::class, 'showCashFlow'])->name('accounting.cash_flow');
        Route::get('/api/accounting/balance-sheet', [FinancialReportController::class, 'balanceSheet'])->name('api.accounting.balance_sheet');
        Route::get('/api/accounting/income-statement', [FinancialReportController::class, 'incomeStatement'])->name('api.accounting.income_statement');
        Route::get('/api/accounting/statement-of-changes', [FinancialReportController::class, 'statementOfChangesInEquity'])->name('api.accounting.statement_of_changes');
        Route::get('/api/accounting/cash-flow', [FinancialReportController::class, 'cashFlowStatement'])->name('api.accounting.cash_flow');

        Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
        Route::resource('/user', UserController::class);

        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
        Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');
    });

    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
        Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');
    });
});
