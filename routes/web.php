<?php

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
    return view('welcome');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    Route::get('/', function() {
        return redirect()->route('voyager.staff.index');
    })->name('voyager.dashboard');

    Route::get('/staff/book/{staff}', [\App\Http\Controllers\StaffController::class, 'book'])
        ->name('voyager.staff.book');
});
