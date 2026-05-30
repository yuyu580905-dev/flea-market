@extends('layouts.app')

@section('title', '商品一覧')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('content')

    <div class="items">

        {{-- タブ --}}
        <div class="items__tabs">
            <a href="/?tab=all&keyword={{ request('keyword') }}"
                class="items__tab {{ request('tab') !== 'mylist' ? 'items__tab--active' : '' }}">
                おすすめ
            </a>

            <a href="/?tab=mylist&keyword={{ request('keyword') }}"
                class="items__tab {{ request('tab') === 'mylist' ? 'items__tab--active' : '' }}">
                マイリスト
            </a>
        </div>

        {{-- 商品一覧 --}}
        <div class="items__grid">
            @foreach ($items as $item)
                <a href="/item/{{ $item->id }}" class="items__card">

                    <div class="items__image-wrapper">
                        @if ($item->is_sold)
                            <div class="items__sold">SOLD</div>
                        @endif

                        <div class="items__image">
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="items__image-tag">
                        </div>
                    </div>

                    <p class="items__name">{{ $item->name }}</p>
                </a>
            @endforeach
        </div>

    </div>
@endsection