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
                    <label for="image" class="form__label">商品画像</label>

                    <div class="sell__image-box">
                        <input type="file" name="image" id="image" class="sell__image-input">
                        <label for="image" class="sell__image-button">
                            画像を選択する
                        </label>
                    </div>
                    @error('image')
                        <div class="form__error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- 商品詳細 --}}
                <div class="form__group sell__section">
                    <p class="sell__section-title">商品の詳細</p>

                    {{-- カテゴリ --}}
                    <label class="form__label">カテゴリー</label>
                    <div class="sell__categories">
                        @foreach($categories as $category)
                            <label for="category-{{ $category->id }}" class="sell__category">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                    id="category-{{ $category->id }}" class="sell__category-input" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                                <span class="sell__category-tag">
                                    {{ $category->name }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('categories')
                        <div class="form__error">
                            {{ $message }}
                        </div>
                    @enderror

                    {{-- 状態 --}}
                    <label for="condition_id" class="form__label">商品の状態</label>
                    <select name="condition_id" id="condition_id" class="form__input" required>
                        <option value="" disabled selected{{ old('condition_id') ? '' : 'selected' }}>
                            選択してください
                        </option>
                        @foreach($conditions as $condition)
                            <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? ' selected' : '' }}>
                                {{ $condition->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('condition_id')
                        <div class="form__error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- 商品名と説明 --}}
                <div class="form__group sell__section">
                    <p class="sell__section-title">商品名と説明</p>

                    <label for="name" class="form__label">商品名</label>
                    <input type="text" name="name" id="name" class="form__input" value="{{ old('name') }}">
                    @error('name')
                        <div class="form__error">
                            {{ $message }}
                        </div>
                    @enderror

                    <label for="brand" class="form__label">ブランド名</label>
                    <input type="text" name="brand" id="brand" class="form__input" value="{{ old('brand') }}">

                    <label for="description" class="form__label">商品の説明</label>
                    <textarea name="description" id="description"
                        class="form__input sell__textarea">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="form__error">
                            {{ $message }}
                        </div>
                    @enderror

                    <label for="price" class="form__label">販売価格</label>
                    <div class="sell__price-box">
                        <span class="sell__price-yen">¥</span>
                        <input type="number" name="price" id="price" min="0" class="sell__price-input"
                            value="{{ old('price') }}">
                    </div>
                    @error('price')
                        <div class="form__error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="form__button">
                    出品する
                </button>

            </form>
        </div>
    </div>
@endsection