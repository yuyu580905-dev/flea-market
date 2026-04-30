<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;

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

Route::middleware(['auth', 'profile.complete'])->group(function () {
    Route::get('/', [ItemController::class, 'index']);
    Route::get('/item/{item}', [ItemController::class, 'show']);
    Route::get('/purchase/{item}', [PurchaseController::class, 'create']);
});

Route::middleware('auth')->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'edit']);
    Route::post('/mypage/profile', [ProfileController::class, 'update']);
});

Route::get('/', [ItemController::class, 'index']);


//表示確認のため
Route::view('/purchase/address/1', 'purchases.address', [
    // action="/purchase/address/{{ $item->id }}" 用
    'item' => (object)[
        'id' => 1
    ],
    // {{ old('postcode', $user->postcode) }} 等の初期表示用
    'user' => (object)[
        'postcode' => '123-4567',
        'address'  => '東京都渋谷区道玄坂',
        'building' => 'サンプルビル101',
    ]
]);

Route::view('/mypage', 'mypage', [
    // $user->name 用
    'user' => (object)[
        'name' => 'テストユーザー'
    ],
    // @foreach($items as $item) 用
    // 3つ分くらいダミーを作っておくとレイアウトが確認しやすいです
    'items' => [
        (object)['name' => '商品A'],
        (object)['name' => '商品B'],
        (object)['name' => '商品C'],
    ]
]);

Route::view('/sell', 'sell', [
    // カテゴリー一覧のダミーデータ
    'categories' => [
        (object)['id' => 1, 'name' => 'ファッション'],
        (object)['id' => 2, 'name' => '家電・スマホ'],
        (object)['id' => 3, 'name' => 'スポーツ'],
        (object)['id' => 4, 'name' => 'ハンドメイド'],
        (object)['id' => 5, 'name' => 'その他'],
    ]
]);
