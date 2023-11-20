@extends('layouts.app')

@push('styles')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('js/home.js') }}" defer></script>
@endpush

@section('content')
    <section class="home-content">
        <section class="opening">
            <h1 class="shine">Welcome to ProjPlanner</h1>
            <p>Your Ultimate Project Planning Platform</p>
        </section>

        <section class="home-center">
            <p>At ProjPlanner, we understand that every project is unique, and planning is the key to success. Whether
                you're a seasoned project manager or embarking on your first venture, we've got you covered.</p>
            <h2 class="shine">Get Started</h2>
            <p>Get started today by signing up for free and take your first step towards turning your ideas into reality.
            </p>
            <a href="{{ url('/login') }}"><button>Sign In</button></a> <a
                href="{{ url('/register') }}"><button>Register</button></a>
        </section>
    </section>
@endsection
