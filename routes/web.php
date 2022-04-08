<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EntrieController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ConclusionController;
use App\Http\Controllers\DependencyController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SetPasswordController;

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

Route::get('setpassword/{email}', [SetPasswordController::class, 'create'])->name('setpassword');
Route::post('setpassword', [SetPasswordController::class, 'store'])->name('setpassword.store');

Route::group(['middleware' => ['auth','is.active']], function(){

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('users', UserController::class)->middleware('can:Lista de usuarios')->name('users');

    Route::get('dependencies', DependencyController::class)->middleware('can:Lista de dependencias')->name('dependencies');

    Route::get('roles', RoleController::class)->middleware('can:Lista de roles')->name('roles');

    Route::get('permissions', PermissionController::class)->middleware('can:Lista de permisos')->name('permissions');

    Route::get('tracking', TrackingController::class)->middleware('can:Lista de seguimientos')->name('tracking');

    Route::get('conclusions', ConclusionController::class)->middleware('can:Lista de conclusiones')->name('conclusions');

    Route::get('entries', [EntrieController::class, 'index'])->middleware('can:Lista de entradas')->name('entries.index');
    Route::get('entries/{entrie}', [EntrieController::class, 'show'])->middleware('can:Ver entrada')->name('entries.show');

});
