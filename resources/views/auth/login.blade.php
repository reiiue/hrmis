@extends('layouts.app')

@section('title', 'Login')

@section('content')
<h2>Login</h2>

@if ($errors->any())
    <div style="color: red">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf
    <label>Username:
        <input type="text" name="username" required>
    </label><br>
    <label>Password:
        <input type="password" name="password" required>
    </label><br>
    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
@endsection
