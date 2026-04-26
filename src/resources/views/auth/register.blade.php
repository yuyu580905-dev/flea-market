@extends('layouts.app')

@section('title', '会員登録')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')

    <div class="auth">

        <div class="form">

            <h1 class="form__heading">
                会員登録
            </h1>

            <form method="POST" action="/register" novalidate>
                @csrf

                <div class="form__group">
                    <label class="form__label" for="name">
                        ユーザー名
                    </label>

                    <input class="form__input" type="text" name="name" id="name" value="{{ old('name') }}">

                    @error('name')
                        <div class="form__error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

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
                    <label class="form__label" for="password">
                        パスワード
                    </label>

                    <input class="form__input" type="password" name="password" id="password">

                    @error('password')
                        <div class="form__error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form__group">
                    <label class="form__label" for="password_confirmation">
                        確認用パスワード
                    </label>

                    <input class="form__input" type="password" name="password_confirmation" id="password_confirmation">

                    @error('password_confirmation')
                        <div class="form__error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button class="form__button">
                    登録する
                </button>

            </form>

            <div class="form__link">
                <a href="/login" class="form__link-text">
                    ログインはこちら
                </a>
            </div>
        </div>

    </div>

@endsection