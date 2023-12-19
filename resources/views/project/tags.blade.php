@php use App\Models\Project; @endphp
@extends('layouts.project')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/project/tags.css') }}">
    <link rel="stylesheet" href="{{ asset('css/users.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/cards.css') }}">

@endpush

@push('scripts')
    <script type="module" src="{{ asset('js/pages/user.js') }}" defer></script>
@endpush

@section('content')
    <section class="team">


        <section class="users-list">
            <header>
                <section class="search">
                    <input type="search" placeholder="&#128269 Search" aria-label="Search" id="search-bar"/>

                </section>
                <section>
                    <span> <i class="fa-solid fa-users"></i>  {{count($tags)}} Tags </span>
                    @can('update',[Project::class,$project])
                        <section class="addUserContainer">
                            <form method="POST" action="{{ route('addUser', ['project' => $project])  }}">
                                {{ csrf_field() }}
                                <input type="email" name="email" placeholder="New member Email" required
                                       value="{{old('email')}}">

                                <button type="submit"><i class="fa-solid fa-user-plus"></i> Add member</button>
                            </form>
                            @if ($errors->has('email'))
                                <span class="error">
                            {{ $errors->first('email') }}
                        </span>
                            @endif
                        </section>
                    @endcan
                </section>
            </header>
            <section class="users">
                @foreach($tags as $tag)
                    @include('partials.tagCard',['tag'=>$tag])
                @endforeach
            </section>
        </section>


    </section>
@endsection