@extends('layouts.app')

@section('title', '商品一覧')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('content')

    <div class="items">

        {{-- タブ --}}
        <div class="items__tabs">
            <a href="/?tab=all">おすすめ</a>
            <a href="/?tab=mylist">マイリスト</a>
        </div>

        {{-- 商品一覧 --}}
        <div class="items__grid">

            @foreach ($items as $item)

                <a href="/items/{{ $item->id }}">

                    <div>
                        <img src="{{ asset('storage/items/' . $item->image) }}">

                        @if ($item->is_sold)
                            <span>Sold</span>
                        @endif
                    </div>

                    <p>{{ $item->name }}</p>

                </a>

            @endforeach

        </div>

    </div>

@endsection