@extends('layouts.app')

@section('title', '商品詳細')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/item-show.css') }}">
@endsection

@section('content')

    <div class="item-detail">

        {{-- 左：画像 --}}
        <div class="item-detail__image">
            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="item-detail__image-element">
        </div>

        {{-- 右：情報 --}}
        <div class="item-detail__content">
            <h1 class="item-detail__name">
                {{ $item->name }}
            </h1>

            <p class="item-detail__brand">
                {{ $item->brand }}
            </p>

            <p class="item-detail__price">
                ¥{{ number_format($item->price) }}
                <span class="item-detail__tax">
                    （税込）
                </span>
            </p>

            {{-- いいね・コメントのアイコン --}}
            <div class="item-detail__meta">
                <div class="item-detail__meta-item">
                    <button type="button" class="item-detail__like-button" data-item-id="{{ $item->id }}">
                        @auth
                            @if ($item->isLikedBy(Auth::user()))
                                <img src="{{ asset('images/icon-heart-pink.png') }}" class="item-detail__icon js-like-icon">
                            @else
                                <img src="{{ asset('images/icon-heart-default.png') }}" class="item-detail__icon js-like-icon">
                            @endif
                        @else
                            <img src="{{ asset('images/icon-heart-default.png') }}" class="item-detail__icon js-like-icon">
                        @endauth
                    </button>

                    <span class="item-detail__meta-count js-like-count">
                        {{ $item->likedUsers->count() }}
                    </span>
                </div>

                <div class="item-detail__meta-item">
                    <img src="{{ asset('images/icon-comment.png') }}" class="item-detail__icon">
                    <span class="item-detail__meta-count js-comment-meta-count">
                        {{ $item->comments->count() }}
                    </span>
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
                <h2 class="item-detail__heading">
                    コメント（<span class="js-comment-count">{{ $item->comments->count() }}</span>）
                </h2>

                <div class="js-comment-list">
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
            </div>

            {{-- コメント投稿 --}}
            <div class="item-detail__section">
                <h3 class="item-detail__subheading">商品へのコメント</h3>

                <form action="/comment/{{ $item->id }}" method="POST" class="js-comment-form">
                    @csrf

                    <textarea name="comment" class="item-detail__textarea js-comment-input">{{ old('comment') }}</textarea>

                    <div class="comment__error js-comment-error"></div>

                    <button type="submit" class="item-detail__button">
                        コメントを送信する
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            /*
            |--------------------------------------------------------------------------
            | いいね機能
            |--------------------------------------------------------------------------
            */

            const likeButton = document.querySelector('.item-detail__like-button');

            if (likeButton) {

                likeButton.addEventListener('click', async () => {

                    const itemId = likeButton.dataset.itemId;

                    try {

                        const response = await fetch(`/item/${itemId}/like`, {

                            method: 'POST',

                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },

                        });

                        // 未ログイン
                        if (response.status === 401) {

                            window.location.replace('/login');
                            return;
                        }

                        // 通信失敗
                        if (!response.ok) {
                            throw new Error('いいね通信失敗');
                        }

                        const data = await response.json();

                        const icon =
                            document.querySelector('.js-like-icon');

                        const count =
                            document.querySelector('.js-like-count');

                        // アイコン変更
                        if (data.liked) {

                            icon.src =
                                "{{ asset('images/icon-heart-pink.png') }}";

                        } else {

                            icon.src =
                                "{{ asset('images/icon-heart-default.png') }}";
                        }

                        // 件数更新
                        count.textContent = data.likes_count;

                    } catch (error) {

                        console.error(error);
                    }
                });
            }

            /*
            |--------------------------------------------------------------------------
            | コメント機能
            |--------------------------------------------------------------------------
            */

            const commentForm =
                document.querySelector('.js-comment-form');

            if (commentForm) {

                commentForm.addEventListener('submit', async (e) => {

                    e.preventDefault();

                    const itemId = "{{ $item->id }}";

                    const input =
                        document.querySelector('.js-comment-input');

                    const errorMessage =
                        document.querySelector('.js-comment-error');

                    try {

                        const response = await fetch(`/comment/${itemId}`, {

                            method: 'POST',

                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },

                            body: new FormData(commentForm),
                        });

                        // 未ログイン
                        if (response.status === 401) {

                            window.location.replace('/login');
                            return;
                        }

                        // バリデーションエラー
                        if (response.status === 422) {

                            const errors = await response.json();

                            errorMessage.textContent =
                                errors.errors.comment[0];

                            return;
                        }

                        // 通信失敗
                        if (!response.ok) {
                            throw new Error('コメント送信失敗');
                        }

                        const data = await response.json();

                        const commentList =
                            document.querySelector('.js-comment-list');

                        /*
                        |--------------------------------------------------------------------------
                        | プロフィール画像
                        |--------------------------------------------------------------------------
                        */

                        let profileImageHtml = '';

                        if (data.user_image) {

                            profileImageHtml = `
                                            <img
                                                src="/storage/profiles/${data.user_image}"
                                                alt="${data.user_name}"
                                                class="item-detail__comment-user-image-element"
                                            >
                                        `;

                        } else {

                            profileImageHtml = `
                                            <div class="item-detail__comment-user-placeholder"></div>
                                        `;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | コメントHTML生成
                        |--------------------------------------------------------------------------
                        */

                        const newComment = `
                                        <div class="item-detail__comment">

                                            <div class="item-detail__comment-user">

                                                <div class="item-detail__comment-user-image">
                                                    ${profileImageHtml}
                                                </div>

                                                <div class="item-detail__comment-user-name">
                                                    ${data.user_name}
                                                </div>

                                            </div>

                                            <div class="item-detail__comment-body">
                                                ${data.comment}
                                            </div>

                                        </div>
                                    `;

                        // コメント追加
                        commentList.insertAdjacentHTML(
                            'beforeend',
                            newComment
                        );

                        // コメント件数更新（見出し）
                        document.querySelector('.js-comment-count')
                            .textContent = data.comments_count;

                        // コメント件数更新（吹き出し下）
                        document.querySelector('.js-comment-meta-count')
                            .textContent = data.comments_count;

                        // textarea初期化
                        input.value = '';

                        // エラーメッセージ削除
                        errorMessage.textContent = '';

                    } catch (error) {

                        console.error(error);
                    }
                });
            }
        });
    </script>
@endsection