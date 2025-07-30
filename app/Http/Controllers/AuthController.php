<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['username' => 'Invalid credentials.']);
        }

        if ($user->status !== 'Active') {
            return back()->withErrors(['username' => 'Account is ' . $user->status]);
        }

        Auth::login($user);
        $request->session()->regenerate(); // ✅ Important for session security

        return redirect()->route('dashboard');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'Employee', // default role
            'status' => 'Active'
        ]);

        Auth::login($user);
        $request->session()->regenerate(); // ✅ Regenerate after login

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();     // ✅ Invalidate session
        $request->session()->regenerateToken(); // ✅ Regenerate CSRF token

        return redirect()->route('login');
    }

    public function index()
{
    return view('pds.index');
}



}
