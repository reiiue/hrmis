@extends('layouts.app')

@section('title', 'Register')

@section('content')
<h2>Register</h2>

@if ($errors->any())
    <div style="color: red">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('register') }}">
    @csrf
    <label>Username:
        <input type="text" name="username" required>
    </label><br>
    <label>Password:
        <input type="password" name="password" required>
    </label><br>
    <label>Confirm Password:
        <input type="password" name="password_confirmation" required>
    </label><br>
    <button type="submit">Register</button>
</form>

<p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
@endsection
