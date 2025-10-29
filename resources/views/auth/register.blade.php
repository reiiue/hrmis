@extends('layouts.app')

@section('title', 'Register')

@section('content')
<h2>Register</h2>

@if ($errors->any())
    <div style="color: red">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('register') }}">
    @csrf
    <label>Email:
        <input type="email" name="email" required>
    </label><br>

    <label>Password:
        <input type="password" name="password" required>
    </label><br>

    <label>Confirm Password:
        <input type="password" name="password_confirmation" required>
    </label><br>

    <label>Role:
        <select name="role" required>
            <option value="">-- Select Role --</option>
            <option value="Admin">Admin</option>
            <option value="HR">HR</option>
            <option value="Employee">Employee</option>
        </select>
    </label><br>

    <button type="submit">Register</button>
</form>

<p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
@endsection
