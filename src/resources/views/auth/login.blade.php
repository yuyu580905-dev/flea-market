@extends('layouts.app')

@section('title', 'ログイン')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')

    <div class="auth">

        <div class="form">

            <h1 class="form__heading">
                ログイン
            </h1>

            <form method="POST" action="/login" novalidate>
                @csrf

                <div class="form__group">
                    <label class="form__label" for="email">
                        メールアドレス
                    </label>

                    <input class="form__input" type="email" name="email" id="email" value="{{ old('email') }}">

                    @error('email')
                        <div class="form__error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form__group">
                    <label class="form__label">
                        パスワード
                    </label>

                    <input class="form__input" type="password" name="password">

                    @error('password')
                        <div class="form__error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button class="form__button">
                    ログインする
                </button>

            </form>

            <div class="form__link">
                <a href="/register" class="form__link-text">
                    会員登録はこちら
                </a>
            </div>
        </div>

    </div>

@endsection