<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\SellController;

// 公開
Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item}', [ItemController::class, 'show']);

// ログイン・メール認証必須（マイページ・いいね・コメント・プロフィール設定・購入(＋購入時住所変更)・出品）
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [MyPageController::class, 'index']);
    Route::post('/item/{item}/like', [LikeController::class, 'toggle']);
    Route::post('/comment/{item}', [CommentController::class, 'store']);
    Route::get('/mypage/profile', [ProfileController::class, 'edit']);
    Route::post('/mypage/profile', [ProfileController::class, 'update']);
    Route::get('/purchase/address/{item}', [PurchaseController::class, 'editAddress']);
    Route::post('/purchase/address/{item}', [PurchaseController::class, 'updateAddress']);
    Route::get('/purchase/{item}', [PurchaseController::class, 'create']);
    Route::post('/purchase/{item}', [PurchaseController::class, 'store']);
    Route::get('/sell', [SellController::class, 'create']);
});
