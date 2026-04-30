@extends('layouts.app')

@section('title', 'プロフィール設定')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')

    <div class="profile">

        <div class="form">

            <h1 class="form__heading">プロフィール設定</h1>

            <form method="POST" action="/mypage/profile" enctype="multipart/form-data" novalidate>
                @csrf

                {{-- 画像 --}}
                <div class="profile__image-area">
                    <div class="profile__image">
                        @if (!empty($user->profile_image))
                            <img src="{{ asset('storage/' . $user->profile_image) }}" class="profile__image-element"
                                alt="プロフィール画像">
                        @endif
                    </div>

                    <label class="profile__image-button">
                        画像を選択する
                        <input type="file" name="profile_image" class="profile__file-input" hidden>
                    </label>
                </div>

                {{-- ユーザー名 --}}
                <div class="form__group">
                    <label class="form__label">ユーザー名</label>
                    <input class="form__input" type="text" name="name" value="{{ old('name', $user->name ?? '') }}">
                    @error('name')
                        <div class="form__error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 郵便番号 --}}
                <div class="form__group">
                    <label class="form__label">郵便番号</label>
                    <input class="form__input" type="text" name="postcode"
                        value="{{ old('postcode', $user->postcode ?? '') }}">
                    @error('postcode')
                        <div class="form__error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 住所 --}}
                <div class="form__group">
                    <label class="form__label">住所</label>
                    <input class="form__input" type="text" name="address"
                        value="{{ old('address', $user->address ?? '') }}">
                    @error('address')
                        <div class="form__error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 建物名 --}}
                <div class="form__group">
                    <label class="form__label">建物名</label>
                    <input class="form__input" type="text" name="building"
                        value="{{ old('building', $user->building ?? '') }}">
                    @error('building')
                        <div class="form__error">{{ $message }}</div>
                    @enderror
                </div>

                <button class="form__button">
                    更新する
                </button>

            </form>

        </div>

    </div>

@endsection