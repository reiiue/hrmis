@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center text-primary mb-4">HRMIS</h4>
        <a href="#">Home</a>
        <a href="#">Profile</a>
        <a href="{{ route('pds.index') }}">PDS</a>
        <a href="#">SALN</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link logout">
                Logout
            </button>
        </form>

    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="mb-4">Welcome to Your Dashboard</h2>

        <div class="card">
            <div class="card-body">
                <p>You are logged in as <strong>{{ Auth::user()->role }}</strong>.</p>
                <p class="mt-2">Use the sidebar to navigate your modules.</p>
            </div>
        </div>
    </div>
</div>
@endsection
