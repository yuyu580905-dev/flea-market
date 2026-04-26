@extends('layouts.app')

@section('title', '商品詳細')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/item-show.css') }}">
@endsection

@section('content')

    <div class="item-detail">

        {{-- 左：画像 --}}
        <div class="item-detail__image">
            <img src="{{ asset('storage/items/' . $item->image) }}" alt="">
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

            {{-- いいね・コメント --}}
            <div class="item-detail__meta">
                <div>♡ {{ $item->likes_count ?? 0 }}</div>
                <div>💬 {{ $item->comments_count ?? 0 }}</div>
            </div>

            {{-- 購入ボタン --}}
            <a href="/purchase/{{ $item->id }}" class="item-detail__button">
                購入手続きへ
            </a>

            {{-- 商品説明 --}}
            <div class="item-detail__section">
                <h2>商品説明</h2>
                <p>{{ $item->description }}</p>
            </div>

            {{-- 商品情報 --}}
            <div class="item-detail__section">
                <h2>商品の情報</h2>

                <p>カテゴリー：
                    @foreach ($item->categories as $category)
                        <span class="item-detail__category">
                            {{ $category->name }}
                        </span>
                    @endforeach
                </p>

                <p>商品の状態：{{ $item->condition }}</p>
            </div>

            {{-- コメント --}}
            <div class="item-detail__section">
                <h2>コメント（{{ $item->comments->count() }}）</h2>

                @foreach ($item->comments as $comment)
                    <div class="item-detail__comment">

                        <div class="item-detail__comment-user">
                            {{ $comment->user->name }}
                        </div>

                        <div class="item-detail__comment-body">
                            {{ $comment->content }}
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- コメント投稿 --}}
            <div class="item-detail__section">

                <h2>商品へのコメント</h2>

                <form method="POST" action="/comment">
                    @csrf

                    <textarea name="content" class="item-detail__textarea"></textarea>

                    <button class="item-detail__button">
                        コメントを送信する
                    </button>

                </form>

            </div>

        </div>

    </div>

@endsection