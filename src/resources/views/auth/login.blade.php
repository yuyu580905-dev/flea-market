@extends('layouts.app')

@section('title', 'ログイン')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')

    <div class="auth-form">

        <h1 class="auth-form__heading">
            ログイン
        </h1>

        <form method="POST" action="/login" novalidate>
            @csrf

            <div class="auth-form__group">
                <label class="auth-form__label" for="email">
                    メールアドレス
                </label>

                <input class="auth-form__input" type="email" name="email" id="email" value="{{ old('email') }}">

                @error('email')
                    <div class="auth-form__error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="auth-form__group">
                <label class="auth-form__label">
                    パスワード
                </label>

                <input class="auth-form__input" type="password" name="password">

                @error('password')
                    <div class="auth-form__error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button class="auth-form__button">
                ログインする
            </button>

        </form>

        <div class="auth-form__link">
            <a href="/register">
                会員登録はこちら
            </a>
        </div>

    </div>

@endsection