@extends('layouts.app')

@section('title', 'マイページ')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')

    <div class="mypage">

        {{-- プロフィール --}}
        <div class="mypage__header">
            <div class="mypage__profile">
                <div class="mypage__avatar">
                    @if (!empty($user->profile->profile_image))
                        <img src="{{ asset('storage/profiles/' . $user->profile->profile_image) }}" class="mypage__avatar-image"
                            alt="プロフィール画像">
                    @endif
                </div>
                <h1 class="mypage__username">{{ $user->name }}</h1>
            </div>

            <a href="/mypage/profile" class="mypage__edit-button">
                プロフィールを編集
            </a>
        </div>

        {{-- タブ --}}
        <div class="mypage__tabs">
            <a href="/mypage?page=sell" class="mypage__tab {{ request('page') !== 'buy' ? 'mypage__tab--active' : '' }}">
                出品した商品
            </a>

            <a href="/mypage?page=buy" class="mypage__tab {{ request('page') === 'buy' ? 'mypage__tab--active' : '' }}">
                購入した商品
            </a>
        </div>

        {{-- 商品一覧 --}}
        <div class="mypage__grid">
            @foreach($items as $item)
                <div class="mypage__card">

                    <div class="mypage__image-wrapper">
                        @if($item->is_sold)
                            <div class="mypage__sold">SOLD</div>
                        @endif

                        <div class="mypage__image">
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="mypage__image-tag">
                        </div>
                    </div>

                    <p class="mypage__item-name">
                        {{ $item->name }}
                    </p>

                </div>
            @endforeach
        </div>

    </div>
@endsection