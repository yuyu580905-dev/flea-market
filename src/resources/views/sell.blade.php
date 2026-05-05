@extends('layouts.app')

@section('title', '商品の出品')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')

    <div class="sell">

        <div class="form">

            <h1 class="form__heading">商品の出品</h1>

            <form method="POST" action="/sell" enctype="multipart/form-data" novalidate>
                @csrf

                {{-- 商品画像 --}}
                <div class="form__group">
                    <label class="form__label">商品画像</label>

                    <div class="sell__image-box">
                        <input type="file" name="image" id="image" class="sell__image-input">
                        <label for="image" class="sell__image-button">
                            画像を選択する
                        </label>
                    </div>
                </div>

                {{-- 商品詳細 --}}
                <div class="form__group sell__section">
                    <p class="sell__section-title">商品の詳細</p>

                    {{-- カテゴリ --}}
                    <label class="form__label">カテゴリー</label>
                    <div class="sell__categories">
                        @foreach($categories as $category)
                            <label class="sell__category">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                    class="sell__category-input">
                                <span class="sell__category-tag">
                                    {{ $category->name }}
                                </span>
                            </label>
                        @endforeach
                    </div>

                    {{-- 状態 --}}
                    <label class="form__label">商品の状態</label>
                    <select name="condition" class="form__input" required>
                        <option value="" disabled selected>選択してください</option>
                        <option value="良好">良好</option>
                        <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                        <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                        <option value="状態が悪い">状態が悪い</option>
                    </select>
                </div>

                {{-- 商品名と説明 --}}
                <div class="form__group sell__section">
                    <p class="sell__section-title">商品名と説明</p>

                    <label class="form__label">商品名</label>
                    <input type="text" name="name" class="form__input">

                    <label class="form__label">ブランド名</label>
                    <input type="text" name="brand" class="form__input">

                    <label class="form__label">商品の説明</label>
                    <textarea name="description" class="form__input sell__textarea"></textarea>

                    <label class="form__label">販売価格</label>
                    <div class="sell__price-box">
                        <span class="sell__price-yen">¥</span>
                        <input type="number" name="price" min="0" class="sell__price-input">
                    </div>
                </div>

                <button type="submit" class="form__button">
                    出品する
                </button>

            </form>
        </div>
    </div>
@endsection