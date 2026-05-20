@extends('layouts.app')

@section('title', '購入画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')

    <div class="purchase">

        <form method="POST" action="/purchase/{{ $item->id }}" class="purchase__form">
            @csrf

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

                {{-- 支払い方法 --}}
                <div class="purchase__section">
                    <p class="purchase__label">支払い方法</p>

                    <select name="payment_method" class="purchase__select" id="payment-select">
                        <option value="" disabled {{ old('payment_method') ? '' : 'selected' }}>
                            選択してください
                        </option>

                        <option value="convenience" {{ old('payment_method') === 'convenience' ? 'selected' : '' }}>
                            コンビニ払い
                        </option>

                        <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>
                            カード支払い
                        </option>
                    </select>
                    @error('payment_method')
                        <div class="purchase__error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 配送先 --}}
                <div class="purchase__section">

                    <div class="purchase__address-header">
                        <p class="purchase__label">配送先</p>
                        <a href="/purchase/address/{{ $item->id }}" class="purchase__change">
                            変更する
                        </a>
                    </div>

                    <p class="purchase__address">
                        〒{{ $address['postcode'] }}<br>
                        {{ $address['address'] }}
                        {{ $address['building'] }}
                    </p>
                    <input type="hidden" name="postcode" value="{{ $address['postcode'] }}">
                    <input type="hidden" name="address" value="{{ $address['address'] }}">
                    <input type="hidden" name="building" value="{{ $address['building'] }}">

                    @error('postcode')
                        <div class="purchase__error">{{ $message }}</div>
                    @enderror

                    @error('address')
                        <div class="purchase__error">{{ $message }}</div>
                    @enderror

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
                        <span id="payment-text">選択してください</span>
                    </div>
                </div>

                <button type="submit" class="purchase__button">
                    購入する
                </button>

            </div>

        </form>

    </div>

    <script>
        const paymentSelect = document.getElementById('payment-select');
        const paymentText = document.getElementById('payment-text');

        function updatePaymentText() {

            if (paymentSelect.selectedIndex !== 0) {

                paymentText.textContent =
                    paymentSelect.options[paymentSelect.selectedIndex].text;

            } else {
                paymentText.textContent = '選択してください';
            }
        }

        updatePaymentText();

        paymentSelect.addEventListener('change', updatePaymentText);
    </script>

@endsection