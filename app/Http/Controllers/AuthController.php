<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin($role = null)
    {
        // Optional: validate only expected roles
        if ($role !== null && !in_array(strtolower($role), ['admin', 'employee'])) {
            abort(404);
        }

        return view('auth.login', ['role' => $role]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'role' => 'nullable|string' // ✅ added for role check
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['username' => 'Invalid credentials.']);
        }

        // ✅ Check if account is active
        if ($user->status !== 'Active') {
            return back()->withErrors(['username' => 'Account is ' . $user->status]);
        }

        // ✅ Verify role if selected from homepage
        if ($request->filled('role') && strtolower($user->role) !== strtolower($request->role)) {
            return back()->withErrors(['username' => 'You are not authorized to log in as ' . ucfirst($request->role) . '.']);
        }

        // ✅ Log in user
        Auth::login($user);
        $request->session()->regenerate();

        // ✅ Redirect based on role
        if ($user->role === 'Admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Welcome, Admin!');
        } elseif ($user->role === 'Employee') {
            return redirect()->route('employee.dashboard')->with('success', 'Welcome, Employee!');
        }
        // fallback
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
