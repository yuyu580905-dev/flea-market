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
            <a class="header__logo" href="/">
                <img class="header__logo-image" src="{{ asset('images/logo.png') }}" alt="COACHTECH">
            </a>
        </div>
    </header>

    <main class="main">
        @yield('content')
    </main>

</body>

</html>