@extends('layouts.app')

@section('title', ucfirst($role ?? 'User') . ' Login')

@section('content')
    <h2>{{ ucfirst($role ?? 'User') }} Login</h2>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 10px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        {{-- ✅ Hidden role input (e.g., admin, employee, hr) --}}
        <input type="hidden" name="role" value="{{ $role }}">

        <label>
            Email:
            <input type="email" name="email" value="{{ old('email') }}" required autofocus>
        </label><br><br>

        <label>
            Password:
            <input type="password" name="password" required>
        </label><br><br>

        <button type="submit">Login</button>
    </form>

    <p style="margin-top: 15px;">
        Don’t have an account?
        <a href="{{ route('register') }}">Register</a>
    </p>

    @if ($role)
        <p><a href="{{ route('home') }}">← Back to role selection</a></p>
    @endif
@endsection
