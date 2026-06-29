<?php

/**
 * APIルーティング定義ファイル
 * 
 * Next.jsなどのフロントエンドから呼び出されるAPIエンドポイントと、
 * それに対応するコントローラーの処理のマッピングを定義します。
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
