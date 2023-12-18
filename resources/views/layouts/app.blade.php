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
    <script src="https://kit.fontawesome.com/f09afb12ac.js" crossorigin="anonymous"></script>
    <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/app.css') }}" rel="stylesheet">
    <script src="https://kit.fontawesome.com/f09afb12ac.js" crossorigin="anonymous"></script>

    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>

    @stack('styles')
    <!-- Scripts -->
    <script type="module" src={{ url('js/app.js') }} defer></script>

    @stack('scripts')

</head>

<body>
    <header>
        <section>
            <h1 id="header_title"><a href="{{ route('home') }}"> <i class="fa-solid fa-bars-progress"></i>
                    ProjPlanner</a></h1>

            @if (Auth::check())
                <a class="user_icon" href="{{ route('profile', ['user' => Auth::user()]) }}"> 
                    <img class="icon avatar" src="{{ auth()->user()->getProfileImage() }}" alt="default user icon">
                </a>
                <a id="logout" href="{{ route('logout') }}"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            @else
                <a class="user_icon" href="{{ route('login') }}"> <img class="icon avatar"
                        src="{{ asset('img/user/default_user.jpg') }}" alt="default user icon"></a>
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


                <li><a href="{{ route('static', ['page' => 'faq']) }}"> <i class="fa-solid fa-question"></i> FAQ</a>
                </li>
                <li><a href="{{ route('static', ['page' => 'about-us']) }}"> <i class="fa-solid fa-address-card"></i>
                        About Us</a></li>
                <li><a href="{{ route('static', ['page' => 'contacts']) }}"><i class="fa-solid fa-message"></i> Contact
                        Us</a></li>

            </ul>
        </section>
        <section>
            <h6>&copy;2023 ProjPlanner All Rights Reserved</h6>
        </section>
    </footer>
</body>

</html>
