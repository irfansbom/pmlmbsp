<?php

use App\Http\Controllers\DataentryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportController;

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
Route::get('/', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/loginweb', [LoginController::class, 'loginweb']);
Route::get('/get_token', [LoginController::class, 'get_token']);
Route::get('/logout', [LoginController::class, 'logout']);
// Route::get('/report', [ReportController::class, 'index', function () {
//     return;
// }]);
Route::post('/updatenks', [DataentryController::class, 'store']);
Route::post('/getnksbypml', [DataentryController::class, 'nks_bypml']);
Route::get('/shownks', [DataentryController::class, 'showbynks']);
Route::get('/showbykab', [DataentryController::class, 'showbykab']);
Route::get('/showall', [DataentryController::class, 'showall']);
Route::get('/nkslog', [DataentryController::class, 'nkslog']);


Route::resource('report', ReportController::class);
Route::get('/reportadmin', [ReportController::class, 'adminkab']);
