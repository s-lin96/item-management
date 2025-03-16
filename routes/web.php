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

// 認証済みユーザーのみがアクセスできるルート
Route::middleware(['auth'])->group(function(){
    // 管理者・一般ユーザー共用
    // ホーム画面
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::prefix('items')->group(function(){
        // 商品一覧(削除済み含まない)
        Route::get('/', [App\Http\Controllers\HomeController::class, 'showItemsTable'])->name('items.table.readonly');
        // 商品詳細
        Route::get('/detail/{id}', [App\Http\Controllers\HomeController::class, 'showDetail'])->name('item.detail');
        // 商品検索
        Route::get('/search-item', [App\Http\Controllers\HomeController::class, 'searchItem'])->name('search.by.allusers');
    });

    // 管理者ユーザーのみがアクセスできるルート
    Route::middleware(['can:isAdmin'])->group(function(){
        Route::prefix('items/admin')->group(function(){
            // 商品一覧(削除済み非表示)
            Route::get('/', [App\Http\Controllers\ItemController::class, 'index'])->name('items.table');
            // 商品一覧(削除済み表示)
            Route::get('/show-deleted', [App\Http\Controllers\ItemController::class, 'showDeleted'])->name('deleted.items.show'); 
            // 商品検索
            Route::get('/search-item', [App\Http\Controllers\ItemController::class, 'searchItem'])->name('search.by.admin');
            // 商品登録
            Route::get('/add', [App\Http\Controllers\ItemController::class, 'create'])->name('item.create'); 
            Route::post('/add', [App\Http\Controllers\ItemController::class, 'store'])->name('item.store');
            // 商品編集
            Route::get('/edit-detail/{id}', [App\Http\Controllers\ItemController::class, 'showItemEdit'])->name('item.edit'); 
            Route::post('/edit-detail/{id}', [App\Http\Controllers\ItemController::class, 'updateItem'])->name('item.update');
            // 商品削除(論理削除)
            Route::get('/delete/{id}', [App\Http\Controllers\ItemController::class, 'delete'])->name('item.delete'); 
            // 削除された商品復元
            Route::get('/restore/{id}', [App\Http\Controllers\ItemController::class, 'restore'])->name('item.restore');
            // 入出庫記録
            Route::get('/record-stock/{id}', [App\Http\Controllers\ItemController::class, 'showStockRecord'])->name('stock.record');
            Route::post('/record-stock/{id}', [App\Http\Controllers\ItemController::class, 'updateStock'])->name('stock.update');
        });

        Route::prefix('users')->group(function(){
            // ユーザー管理(削除済み非表示)
            Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('users.table');
            // ユーザー管理(削除済み表示)
            Route::get('/show-deleted', [App\Http\Controllers\UserController::class, 'showDeleted'])->name('deleted.users.show');
            // ユーザー編集
            Route::get('/edit-user/{id}', [App\Http\Controllers\UserController::class, 'showUserEdit'])->name('user.edit');
            Route::post('/edit-user/{id}', [App\Http\Controllers\UserController::class, 'updateUser'])->name('user.update');
            // ユーザー論理削除
            Route::get('/delete/{id}', [App\Http\Controllers\UserController::class, 'delete'])->name('user.delete');
            // 削除されたユーザー復元
            Route::get('/restore/{id}', [App\Http\Controllers\UserController::class, 'restore'])->name('user.restore');
        });
    });
});