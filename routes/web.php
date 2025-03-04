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

// 管理者ユーザーのみがアクセスできるルート
// Route::prefix('items')->group(function () {});
Route::get('/items/admin', [App\Http\Controllers\ItemController::class, 'index'])->name('items.table');
Route::get('/items/admin/add', [App\Http\Controllers\ItemController::class, 'create'])->name('item.create');
Route::post('/items/admin/add', [App\Http\Controllers\ItemController::class, 'store'])->name('item.store');

/**
 * bladeファイル未作成
 */
// 共通画面
Route::get('/items', [App\Http\Controllers\HomeController::class, 'showItemsTable'])->name('items.table.readonly');
Route::get('/items/detail', [App\Http\Controllers\HomeController::class, 'showDetail'])->name('item.detail');
// 管理者ユーザーのみアクセス可
Route::get('/items/admin/edit-detail/{id}', [App\Http\Controllers\ItemController::class, 'showItemEdit'])->name('item.edit'); // 商品編集
Route::post('/items/admin/edit-detail', [App\Http\Controllers\ItemController::class, 'updateItem'])->name('item.update');
Route::get('/items/admin/delete/{id}', [App\Http\Controllers\ItemController::class, 'delete'])->name('item.delete'); // 商品論理削除
Route::get('/items/admin/record-stock/{id}', [App\Http\Controllers\ItemController::class, 'showStockRecord'])->name('stock.record'); // 入出庫記録
Route::post('/items/admin/record-stock', [App\Http\Controllers\ItemController::class, 'updateStock'])->name('stock.update');
Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.table'); // ユーザー管理
Route::get('/users/{id}', [App\Http\Controllers\UserController::class, 'showUserEdit'])->name('user.edit'); // ユーザー編集
Route::get('/users/delete/{id}', [App\Http\Controllers\UserController::class, 'delete'])->name('user.delete'); // ユーザー論理削除
Route::post('/users/{id}', [App\Http\Controllers\UserController::class, 'updateUser'])->name('user.update');