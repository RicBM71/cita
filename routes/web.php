<?php

use App\Models\Cita;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
})->name('home');

Route::get('/test', function () {

    return Cita::with(['estado'])
        ->paciente(806)
        ->orderBy('fecha', 'desc')
        ->get()->take(5);

})->name('test');

Route::middleware(['auth:sanctum', 'verified', 'lortad'])->get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
Route::middleware(['auth:sanctum', 'verified'])->get('lortad', [App\Http\Controllers\LortadController::class, 'index'])->name('lortad');
Route::middleware(['auth:sanctum', 'verified'])->put('lortad', [App\Http\Controllers\LortadController::class, 'update'])->name('lortad.update');

Route::group([
    'prefix'     => 'admin',
    // 'namespace' => 'Mto',
    //'middleware' => ['auth:sanctum','verified','password.confirm']],
    'middleware' => ['auth:sanctum', 'verified', 'lortad', 'role:root']],
    function () {

        //Route::middleware('password.confirm')->resource('users', App\Http\Controllers\Admin\UsersController::class);
        Route::resource('users', App\Http\Controllers\Admin\UsersController::class);
        Route::delete('users/{user}/photo/delete', [App\Http\Controllers\Admin\UsersPhotoController::class, 'destroy'])->name('photo.destroy');

        Route::get('users/{user}/roles', [App\Http\Controllers\Admin\UsersRolesController::class, 'show'])->name('user.roles.show');
        Route::put('users/{user}/roles', [App\Http\Controllers\Admin\UsersRolesController::class, 'update'])->name('user.roles.update');
        Route::get('users/{user}/permission', [App\Http\Controllers\Admin\UsersPermissionsController::class, 'show'])->name('user.permissions.show');
        Route::put('users/{user}/permission', [App\Http\Controllers\Admin\UsersPermissionsController::class, 'update'])->name('user.permissions.update');

        Route::resource('roles', App\Http\Controllers\Admin\RolesController::class);

    }
);

Route::middleware(['auth:sanctum', 'verified', 'lortad'])->get('book/{fecha}/{turno}', [App\Http\Controllers\ReservasController::class, 'index'])->name('book');
Route::middleware(['auth:sanctum', 'verified', 'lortad'])->post('book/create', [App\Http\Controllers\ReservasController::class, 'store'])->name('book.store');
Route::middleware(['auth:sanctum', 'verified', 'lortad'])->delete('book/{cita}', [App\Http\Controllers\ReservasController::class, 'destroy'])->name('book.destroy');
//Route::resource('book', App\Http\Controllers\Admin\ReservasController::class);
