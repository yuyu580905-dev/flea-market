@extends('layouts.app')

@section('title', '商品詳細')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/item-show.css') }}">
@endsection

@section('content')

    <div class="item-detail">

        {{-- 左：画像 --}}
        <div class="item-detail__image">
            <img src="{{ asset('storage/items/' . $item->image) }}" alt="{{ $item->name }}"
                class="item-detail__image-element">
        </div>

        {{-- 右：情報 --}}
        <div class="item-detail__content">
            <h1 class="item-detail__name">
                {{ $item->name }}
            </h1>

            <p class="item-detail__brand">
                {{ $item->brand ?? 'ブランド名' }}
            </p>

            <p class="item-detail__price">
                ¥{{ number_format($item->price) }}（税込）
            </p>

            {{-- いいね・コメントのアイコン --}}
            <div class="item-detail__meta">
                <div class="item-detail__meta-item">
                    <form action="/item/{{ $item->id }}/like" method="POST">
                        @csrf
                        <button type="submit" class="item-detail__like-button">
                            @auth
                                @if ($item->isLikedBy(Auth::user()))
                                    <img src="{{ asset('images/icon-heart-pink.png') }}" class="item-detail__icon">
                                @else
                                    <img src="{{ asset('images/icon-heart-default.png') }}" class="item-detail__icon">
                                @endif
                            @else
                                <img src="{{ asset('images/icon-heart-default.png') }}" class="item-detail__icon">
                            @endauth
                        </button>
                    </form>

                    <span class="item-detail__meta-count">
                        {{ $item->likedUsers->count() }}
                    </span>
                </div>

                <div class="item-detail__meta-item">
                    <img src="{{ asset('images/icon-comment.png') }}" class="item-detail__icon">
                    <span class="item-detail__meta-count">
                        {{ $item->comments->count() }}</span>
                </div>
            </div>

            {{-- 購入ボタン --}}
            <a href="/purchase/{{ $item->id }}" class="item-detail__button">
                購入手続きへ
            </a>

            {{-- 商品説明 --}}
            <div class="item-detail__section">
                <h2 class="item-detail__heading">商品説明</h2>
                <p>{{ $item->description }}</p>
            </div>

            <div class="item-detail__section">
                <h2 class="item-detail__heading">商品の情報</h2>

                {{-- カテゴリー --}}
                <div class="item-detail__row">
                    <span class="item-detail__label">カテゴリー</span>
                    <div class="item-detail__value">
                        @foreach ($item->categories as $category)
                            <span class="item-detail__category">
                                {{ $category->name }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- 商品の状態 --}}
                <div class="item-detail__row">
                    <span class="item-detail__label">商品の状態</span>
                    <span class="item-detail__value">
                        {{ $item->condition->name }}
                    </span>
                </div>
            </div>

            {{-- コメント --}}
            <div class="item-detail__section">
                <h2 class="item-detail__heading">コメント（{{ $item->comments->count() }}）</h2>

                @foreach ($item->comments as $comment)
                    <div class="item-detail__comment">
                        <div class="item-detail__comment-user">
                            <div class="item-detail__comment-user-image">

                                @if ($comment->user->profile && $comment->user->profile->profile_image)
                                    <img src="{{ asset('storage/profiles/' . $comment->user->profile->profile_image) }}"
                                        alt="{{ $comment->user->name }}" class="item-detail__comment-user-image-element">
                                @else
                                    <div class="item-detail__comment-user-placeholder"></div>
                                @endif

                            </div>

                            <div class="item-detail__comment-user-name">
                                {{ $comment->user->name }}
                            </div>
                        </div>

                        <div class="item-detail__comment-body">
                            {{ $comment->comment }}
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- コメント投稿 --}}
            <div class="item-detail__section">
                <h3 class="item-detail__subheading">商品へのコメント</h3>
                <form action="/comment/{{ $item->id }}" method="POST">
                    @csrf

                    <textarea name="comment" class="item-detail__textarea">{{ old('comment') }}</textarea>

                    @error('comment')
                        <div class="comment__error">
                            {{ $message }}
                        </div>
                    @enderror

                    <button type="submit" class="item-detail__button">
                        コメントを送信する
                    </button>
                </form>
            </div>

        </div>

    </div>

@endsection