<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MyPageController;

// 公開
Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item}', [ItemController::class, 'show']);

// ログイン・メール認証必須（マイページ・いいね・コメント・プロフィール設定・購入）
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [MyPageController::class, 'index']);
    Route::post('/item/{item}/like', [LikeController::class, 'toggle']);
    Route::post('/comment/{item}', [CommentController::class, 'store']);
    Route::get('/mypage/profile', [ProfileController::class, 'edit']);
    Route::post('/mypage/profile', [ProfileController::class, 'update']);
    Route::get('/purchase/{item}', [PurchaseController::class, 'create']);
});













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

// パスは任意ですが、分かりやすく /verify としています
Route::view('/verify', 'auth.verify');
