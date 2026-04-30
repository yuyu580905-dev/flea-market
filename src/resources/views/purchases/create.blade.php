@extends('layouts.app')

@section('title', '購入画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')

    <div class="purchase">

        {{-- 左側 --}}
        <div class="purchase__left">

            {{-- 商品情報 --}}
            <div class="purchase__item">
                <img src="{{ asset('storage/items/' . $item->image) }}" class="purchase__image">

                <div class="purchase__info">
                    <p class="purchase__name">
                        {{ $item->name }}
                    </p>
                    <p class="purchase__price">
                        ¥{{ number_format($item->price) }}
                    </p>
                </div>
            </div>

            <hr>

            {{-- 支払い方法 --}}
            <div class="purchase__section">
                <p class="purchase__label">支払い方法</p>

                <select name="payment_method" class="purchase__select">
                    <option value="convenience">コンビニ払い</option>
                    <option value="card">カード支払い</option>
                </select>
            </div>

            <hr>

            {{-- 配送先 --}}
            <div class="purchase__section">

                <div class="purchase__address-header">
                    <p class="purchase__label">配送先</p>
                    <a href="/address/edit" class="purchase__change">
                        変更する
                    </a>
                </div>

                <p class="purchase__address">
                    〒{{ optional(auth()->user()->profile)->postcode }}<br>
                    {{ optional(auth()->user()->profile)->address }}
                    {{ optional(auth()->user()->profile)->building }}
                </p>

            </div>

        </div>


        {{-- 右側 --}}
        <div class="purchase__right">

            <div class="purchase__summary">

                <div class="purchase__row">
                    <span>商品代金</span>
                    <span>¥{{ number_format($item->price) }}</span>
                </div>

                <div class="purchase__row">
                    <span>支払い方法</span>
                    <span>コンビニ払い</span>
                </div>

            </div>

            <form method="POST" action="/purchase/{{ $item->id }}">
                @csrf

                <button class="purchase__button">
                    購入する
                </button>
            </form>

        </div>

    </div>

@endsection