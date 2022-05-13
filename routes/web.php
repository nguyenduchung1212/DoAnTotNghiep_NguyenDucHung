<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceImportController;
use App\Http\Controllers\InvoiceExportController;
use App\Http\Controllers\StatisticalController;

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

//Admin
Route::prefix('admin')->group(function () {

    Route::get('/login',                                    [AuthController::class, 'initScreenLoginAdmin'])            ->name('screen_admin_login');
    Route::post('/login',                                   [AuthController::class, 'loginAdmin'])                      ->name('admin_login');

    Route::get('/forgot-password',                          [AuthController::class, 'initScreenForgotPasswordAdmin'])   ->name('screen_admin_forgot_password');
    Route::post('/forgot-password',                         [AuthController::class, 'forgotPasswordAmin'])              ->name('admin_forgot_password');
    Route::get('/reset-password',                           [AuthController::class, 'initScreenUpdatePasswordAdmin'])   ->name('screen_admin_reset_password');
    Route::post('/update-password',                         [AuthController::class, 'updatePasswordAdmin'])             ->name('admin_update_password');

    //Admin Authenticate
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/home',                                 [AuthController::class, 'indexAdmin'])                      ->name('screen_admin_home');
        Route::get('/logout',                               [AuthController::class, 'logoutAdmin'])                     ->name('admin_logout');

        //Admin invoice import
        Route::get('invoice-import/pay/{invoice_import}',   [InvoiceImportController::class, 'pay'])                    ->name('admin.invoice_import.pay');
        Route::resource('invoice-import',                   InvoiceImportController::class,                             ['names' => 'admin.invoice_import']);

        //Admin invoice export
        Route::get('order',                                 [InvoiceExportController::class, 'orders'])                 ->name('admin.invoice_export.order');
        Route::get('order/{id}',                            [InvoiceExportController::class, 'order'])                  ->name('admin.invoice_export.order_view');
        Route::get('accept-order/{id}',                     [InvoiceExportController::class, 'acceptOrder'])            ->name('admin.invoice_export.accept_order');
        Route::get('cancel-order/{id}',                     [InvoiceExportController::class, 'cancelOrder'])            ->name('admin.invoice_export.cancel_order');
        Route::get('invoice',                               [InvoiceExportController::class, 'invoices'])               ->name('admin.invoice_export.invoice');
        Route::get('invoice{id}',                           [InvoiceExportController::class, 'invoice'])                ->name('admin.invoice_export.invoice_view');
        Route::get('up-status-ship/{id}',                   [InvoiceExportController::class, 'upStatusShip'])           ->name('admin.invoice_export.up_status_ship');
        Route::get('close-order',                           [InvoiceExportController::class, 'closeOrders'])            ->name('admin.invoice_export.close_orders');
        Route::get('close-order/{id}',                      [InvoiceExportController::class, 'closeOrder'])             ->name('admin.invoice_export.close_order');

        //Admin statistical
        Route::get('statistical-products',                  [StatisticalController::class, 'statisticalProduct'])       ->name('admin.statistical.products');
        Route::get('statistical-invoices',                  [StatisticalController::class, 'statisticalInvoice'])       ->name('admin.statistical.invoices');
        Route::get('statistical-users',                     [StatisticalController::class, 'statisticalUser'])          ->name('admin.statistical.users');
        
        //Admin product
        Route::resource('brand',                            BrandController::class,                                     ['names' => 'admin.brand']);
        Route::resource('category',                         CategoryController::class,                                  ['names' => 'admin.category']);
        Route::resource('product',                          ProductController::class,                                   ['names' => 'admin.product']);

        //Admin account
        Route::resource('account',                          AdminController::class,                                     ['names' => 'admin.account']);
    });
});

//User
Route::get('/register',                                     [AuthController::class, 'initScreenRegister'])              ->name('screen_register');
Route::post('/register',                                    [AuthController::class, 'register'])                        ->name('register');
Route::get('/login',                                        [AuthController::class, 'initScreenLogin'])                 ->name('screen_login');
Route::post('/login',                                       [AuthController::class, 'login'])                           ->name('login');
Route::get('/',                                             [AuthController::class, 'index'])                           ->name('screen_home');

Route::get('/forgot-password',                              [AuthController::class, 'initScreenForgotPassword'])        ->name('screen_forgot_password');
Route::post('/forgot-password',                             [AuthController::class, 'forgotPassword'])                  ->name('forgot_password');
Route::get('/reset-password',                               [AuthController::class, 'initScreenUpdatePassword'])        ->name('screen_reset_password');
Route::post('/update-password',                             [AuthController::class, 'updatePassword'])                  ->name('update_password');

//User Authenticate
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/logout',                                   [AuthController::class, 'logout'])                           ->name('logout');
});
