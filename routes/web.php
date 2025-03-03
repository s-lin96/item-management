<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::prefix('items')->group(function () {});
Route::get('/items', [App\Http\Controllers\ItemController::class, 'index'])->name('items.table');
Route::get('/items/add', [App\Http\Controllers\ItemController::class, 'create'])->name('item.create');
Route::post('/items/add', [App\Http\Controllers\ItemController::class, 'store'])->name('item.store');