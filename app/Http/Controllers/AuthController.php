<?php

/**
 * 認証コントローラー
 * 
 * ユーザーの新規登録（バリデーション、パスワードハッシュ化、データベース保存、
 * Sanctum（API認証）アクセストークン発行）処理を制御します。
 */

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. バリデーション（入力チェック）
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'language' => 'required|string|max:10',
        ]);

        // 入力に問題があれば、エラーをJSON形式で返す
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. ユーザー登録
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'language' => $request->language,
        ]);

        // 3. アクセストークン（鍵）の発行
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. レスポンスの返却
        return response()->json([
            'message' => 'User registered successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }
}
