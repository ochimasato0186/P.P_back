<?php

/**
 * ユーザーテーブル作成マイグレーション
 * 
 * ユーザー登録に必要な基本情報（名前、メールアドレス、ハッシュ化パスワード、選択言語等）の
 * データベース構造を定義しています。
 * 
 * フロントからトークンを送信し、バックエンドでトークンを検証することで、ユーザーの認証を行っています。
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /** usersテーブル */
        Schema::create('users', function (Blueprint $table) {
            $table->id(); /** ユーザーID（主キー） */
            $table->string('name'); /** ユーザー名 */
            $table->string('email')->unique(); /** メールアドレス */
            $table->timestamp('email_verified_at')->nullable(); /** メール確認日時（未確認ならNULL） */
            $table->string('password'); /** パスワード */
            $table->string('language')->default('日本語'); /** 選択言語 */
            $table->rememberToken();/** ログイン状態を保持するトークン */
            $table->timestamps();
        });

        /** パスワードリセット用トークンテーブル */
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); /** メールアドレス */
            $table->string('token'); /** トークン */
            $table->timestamp('created_at')->nullable(); /** 作成日時 */
        });

        /** セッション情報保存テーブル */
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); /** セッションID */
            $table->foreignId('user_id')->nullable()->index(); /** ユーザーID */
            $table->string('ip_address', 45)->nullable(); /** IPアドレス */
            $table->text('user_agent')->nullable(); /** ブラウザ情報 */
            $table->longText('payload'); /** セッションデータ */
            $table->integer('last_activity')->index(); /** 最終アクティビティ日時 */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
