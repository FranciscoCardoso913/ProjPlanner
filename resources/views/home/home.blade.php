@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
<header>
    <h1>
        Your Projects
    </h1>
</header>

<section class="projects">
    @foreach($projects as $project)
        <div class="project">
            <h2>Name: {{ $project->title }}</h2>
            <h2>Deadline: {{ $project->deadline->format('Y-m-d') }}</h2>
            <a href=""> Go to Project</a>
        </div>
    @endforeach
</section>

@endsection

