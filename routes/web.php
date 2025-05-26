<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AdminsController;
use App\Http\Controllers\Backend\Auth\ForgotPasswordController;
use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\ControlesPreviosController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\EstadosController;
use App\Http\Controllers\Backend\EstructurasDocumentosHabilitantesController;
use App\Http\Controllers\Backend\EstructurasFormatoPagoController;
use App\Http\Controllers\Backend\EstructurasLiquidacionEconomicaController;
use App\Http\Controllers\Backend\EstructurasResumenRemesaController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\ExpedientesController;
use App\Http\Controllers\Backend\ProteccionesController;
use App\Http\Controllers\Backend\TiposRespuestaController;
use App\Http\Controllers\Backend\SemaforosController;
use App\Http\Controllers\Backend\ReportesController;
use App\Http\Controllers\Backend\TiposFormatoController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', 'HomeController@redirectAdmin')->name('index');
Route::get('/home', 'HomeController@index')->name('home');

Route::post('/getControlesPreviosByFilters','backend\ControlesPreviosController@getControlesPreviosByFilters')->middleware('auth:admin');
Route::post('/getFormatoByTipoFormato','backend\ControlesPreviosController@getFormatoByTipoFormato')->middleware('auth:admin');
Route::post('/getControlesPreviosByTipoFormato','backend\ReportesController@getControlesPreviosByTipoFormato')->middleware('auth:admin');
Route::post('/generarReporteById','backend\ReportesController@generarReporteById')->middleware('auth:admin');

/**
 * Admin routes
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('roles', RolesController::class);
    Route::resource('admins', AdminsController::class);
    // Configuraciones
    Route::resource('tiposFormato', TiposFormatoController::class);
    Route::resource('estructurasFormatoPago', EstructurasFormatoPagoController::class);
    Route::resource('estructurasDocumentosHabilitantes', EstructurasDocumentosHabilitantesController::class);
    Route::resource('estructurasResumenRemesa', EstructurasResumenRemesaController::class);
    Route::resource('estructurasLiquidacionEconomica', EstructurasLiquidacionEconomicaController::class);
    
    Route::resource('controlesPrevios', ControlesPreviosController::class);
    Route::resource('reportes', ReportesController::class);

    // Login Routes.
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login/submit', [LoginController::class, 'login'])->name('login.submit');

    // Logout Routes.
    Route::post('/logout/submit', [LoginController::class, 'logout'])->name('logout.submit');

    // Forget Password Routes.
    //Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    //::post('/password/reset/submit', [ForgotPasswordController::class, 'reset'])->name('password.update');

    
})->middleware('auth:admin');
