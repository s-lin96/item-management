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

// 管理者向・一般ユーザー共用
Route::get('/items', [App\Http\Controllers\HomeController::class, 'showItemsTable'])->name('items.table.readonly');
Route::get('/items/detail/{id}', [App\Http\Controllers\HomeController::class, 'showDetail'])->name('item.detail');
Route::get('/items/search-item', [App\Http\Controllers\HomeController::class, 'searchItem'])->name('search.by.allusers');

// 管理者ユーザーのみがアクセスできるルート
Route::middleware(['can:isAdmin'])->group(function(){
    Route::get('/items/admin', [App\Http\Controllers\ItemController::class, 'index'])->name('items.table'); // 商品一覧(削除済み非表示)
    Route::get('/items/admin/show-deleted', [App\Http\Controllers\ItemController::class, 'showDeleted'])->name('deleted.items.show'); // 商品一覧(削除済み表示)
    Route::get('/items/admin/search-item', [App\Http\Controllers\ItemController::class, 'searchItem'])->name('search.by.admin');

    Route::get('/items/admin/add', [App\Http\Controllers\ItemController::class, 'create'])->name('item.create'); // 商品登録フォーム
    Route::post('/items/admin/add', [App\Http\Controllers\ItemController::class, 'store'])->name('item.store'); // 商品登録

    Route::get('/items/admin/edit-detail/{id}', [App\Http\Controllers\ItemController::class, 'showItemEdit'])->name('item.edit'); // 商品編集フォーム
    Route::post('/items/admin/edit-detail/{id}', [App\Http\Controllers\ItemController::class, 'updateItem'])->name('item.update'); // 商品編集

    Route::get('/items/admin/delete/{id}', [App\Http\Controllers\ItemController::class, 'delete'])->name('item.delete'); // 商品論理削除
    Route::get('/items/admin/restore/{id}', [App\Http\Controllers\ItemController::class, 'restore'])->name('item.restore');
    Route::get('/items/admin/record-stock/{id}', [App\Http\Controllers\ItemController::class, 'showStockRecord'])->name('stock.record'); // 入出庫記録
    Route::post('/items/admin/record-stock/{id}', [App\Http\Controllers\ItemController::class, 'updateStock'])->name('stock.update');

    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.table'); // ユーザー管理(削除済み非表示)
    Route::get('/users/show-deleted', [App\Http\Controllers\UserController::class, 'showDeleted'])->name('deleted.users.show'); // ユーザー管理(削除済み表示)
    Route::get('/users/edit-user/{id}', [App\Http\Controllers\UserController::class, 'showUserEdit'])->name('user.edit'); // ユーザー編集
    Route::post('/users/edit-user/{id}', [App\Http\Controllers\UserController::class, 'updateUser'])->name('user.update');
    Route::get('/users/delete/{id}', [App\Http\Controllers\UserController::class, 'delete'])->name('user.delete'); // ユーザー論理削除
});
