@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <h2>Login</h2>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 10px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

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

@endsection
