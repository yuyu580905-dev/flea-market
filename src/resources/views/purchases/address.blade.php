@extends('layouts.app')

@section('title', '住所変更')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')

    <div class="address">

        <div class="form">

            <h1 class="form__heading">
                住所の変更
            </h1>

            <form method="POST" action="/purchase/address/{{ $item->id }}" novalidate>
                @csrf

                <div class="form__group">
                    <label class="form__label">郵便番号</label>
                    <input class="form__input" type="text" name="postcode" value="{{ old('postcode', $user->postcode) }}">
                    @error('postcode')
                        <div class="form__error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form__group">
                    <label class="form__label">住所</label>
                    <input class="form__input" type="text" name="address" value="{{ old('address', $user->address) }}">
                    @error('address')
                        <div class="form__error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form__group">
                    <label class="form__label">建物名</label>
                    <input class="form__input" type="text" name="building" value="{{ old('building', $user->building) }}">
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