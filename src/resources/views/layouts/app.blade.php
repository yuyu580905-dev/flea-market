<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">

    @yield('css')
</head>

<body>

    <header class="header">
        <div class="header__inner header__inner--between">

            <!-- ロゴ -->
            <a class="header__logo" href="/">
                <img class="header__logo-image" src="{{ asset('images/logo.png') }}" alt="COACHTECH">
            </a>

            <!-- 検索 -->
            <form class="header__search" action="/" method="GET">
                <input class="header__search-input" type="text" name="keyword" value="{{ request('keyword') }}"
                    placeholder="なにをお探しですか？">
            </form>

            <!-- ナビ -->
            <nav class="header__nav">

                @auth
                    <form action="/logout" method="POST" class="header__logout-form">
                        @csrf
                        <button type="submit" class="header__link">ログアウト</button>
                    </form>
                @else
                    <a href="/login" class="header__link">ログイン</a>
                @endauth

                <a href="/mypage" class="header__link">マイページ</a>
                <a href="/sell" class="header__button">出品</a>
            </nav>

        </div>
    </header>

    <main class="main">
        @yield('content')
    </main>

    @yield('js')

</body>

</html>