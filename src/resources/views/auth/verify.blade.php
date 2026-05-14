@extends('layouts.app-guest')

@section('title', 'メール認証')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/verify.css') }}">
@endsection

@section('content')

    <div class="verify">

        <div class="form">

            <h1 class="verify__message">
                登録していただいたメールアドレスに認証メールを送付しました。<br>
                メール認証を完了してください。
            </h1>

            {{-- 認証ボタン --}}
            <div class="form__group verify__center">
                <a href="https://mailtrap.io/home" class="verify__button">
                    認証はこちらから
                </a>
            </div>

            {{-- 再送 --}}
            <div class="form__link">
                <form action="/email/verification-notification" method="POST">
                    @csrf
                    <button type="submit" class="verify__resend">
                        認証メールを再送する
                    </button>
                </form>
            </div>

        </div>

    </div>
@endsection