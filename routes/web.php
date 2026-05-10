<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpinController;
use App\Http\Controllers\StrapController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\LeadImportController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;

use App\Imports\OrdersImport;
use Maatwebsite\Excel\Facades\Excel;

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
    return view('welcome');
});


Route::get('/', [SpinController::class, 'showForm'])->name('spin.form');
Route::get('/services', function () {
    return view('spin.services');
})->name('spin.services');
Route::post('/start', [SpinController::class, 'startSpin'])->name('spin.start');
Route::get('/result', [SpinController::class, 'showResult'])->name('spin.result');
Route::get('/wholesale', [SpinController::class, 'showWholesaleForm'])->name('wholesale.show');
Route::post('/wholesale', [SpinController::class, 'submit'])->name('wholesale.submit');
Route::post('/api/spin', [SpinController::class, 'apiSpin'])->name('spin.api');

// Payment routes
Route::post('/payment/initiate', [PaymentController::class, 'initiatePayment'])->name('payment.initiate');
Route::post('/payment/callback', [PaymentController::class, 'handleCallback'])->name('payment.callback');
Route::resource('straps', StrapController::class);
// Export: images by size where stock > 0
Route::get('products/download-images', [ProductController::class, 'downloadImages'])
    ->name('products.download-images');
Route::resource('products', ProductController::class)->except(['show']);

Route::post('/import-orders', function () {
     ini_set('max_execution_time', 300); 
    Excel::import(new OrdersImport, request()->file('file'));

    return "Import Success ✅";
});
Route::get('/upload', function () {
    return view('upload');
});

Route::get('/print', [PrintController::class, 'index'])->name('print.index');
Route::post('/import', [PrintController::class, 'import'])->name('import');
Route::get('/export', [PrintController::class, 'export'])->name('export');
Route::get('/delete', [PrintController::class, 'delete'])->name('delete');
Route::get('/bulk', [PrintController::class, 'bulk'])->name('bulk');

// import
Route::get('/import-leads', [LeadImportController::class, 'showUploadPage']);
Route::post('/import-leads', [LeadImportController::class, 'importExcel'])->name('import.excel');

// search
Route::get('/search', [LeadImportController::class, 'showSearchPage']);
Route::get('/search/result', [LeadImportController::class, 'search'])->name('search.result');

Route::get('/dashboard', [LeadImportController::class, 'index'])->name('dashboard');

Route::get('/verification-queue', [VerificationController::class,'queue']);
Route::post('/verify-order/{id}', [VerificationController::class,'verifyOrder']);
Route::get('/dashboard-summary',[DashboardController::class,'summary']);
Route::get('/risky-customers',[DashboardController::class,'riskyCustomers']);
Route::get('/trusted-customers',[DashboardController::class,'trustedCustomers']);
Route::get('/orders-by-channel',[DashboardController::class,'ordersByChannel']);
Route::get('/returns-by-category',[DashboardController::class,'returnsByCategory']);
