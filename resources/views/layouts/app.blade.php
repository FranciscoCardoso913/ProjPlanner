<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ProjPlanner') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Styles -->
    <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/app.css') }}" rel="stylesheet">
    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>

    @stack('styles')
    <!-- Scripts -->
    @stack('scripts')

</head>
<body>
    <header>
        <section>
            <h1 id="header_title">ProjPlanner</h1>
            @if (Auth::check())
                <a id="logout" href="{{ route('logout') }}">Logout</a>
                <a class="user_icon" href="{{ route('profile', ['usrId' => Auth::id()]) }}"> 
                    <img class="icon avatar" src="{{ asset('img/default_user.png') }}" alt="default user icon">
                </a>
            @else
                <a class="user_icon" href="{{ route('login') }}"> <img class="icon"
                        src="{{ asset('img/default_user.png') }}" alt="default user icon"></a>
            @endif
        </section>
        @if (View::hasSection('navbar'))
            <nav>
                <ul>
                    @yield('navbar')
                </ul>
            </nav>
        @endif

    </header>
    <main>

        <section id="content">
            @yield('content')
        </section>

        </main>
        <footer>
            <section>
                <ul>


                    <li><a href="{{ route('static',['page' => 'faq'])}}">FAQ</a></li>
                    <li><a href="{{ route('static',['page' => 'about-us'])}}">About Us</a></li>
                    <li><a href="{{ route('static',['page' => 'contacts'])}}">Contact Us</a></li>

                </ul>
            </section>
            <section><h6>&copy;2023 Project Planer All rights reserve</h6></section>
        </footer>
    </body>
</html>
