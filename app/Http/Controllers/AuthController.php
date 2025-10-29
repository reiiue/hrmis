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
        // Allow admin, employee, and hr
        if ($role !== null && !in_array(strtolower($role), ['admin', 'employee', 'hr'])) {
            abort(404);
        }

        return view('auth.login', ['role' => $role]);
    }


public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required',
        'role' => 'nullable|string'
    ]);

    $user = User::where('username', $request->username)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->withErrors(['username' => 'Invalid credentials.']);
    }

    if ($user->status !== 'Active') {
        return back()->withErrors(['username' => 'Account is ' . $user->status]);
    }

    if ($request->filled('role') && strtolower($user->role) !== strtolower($request->role)) {
        return back()->withErrors(['username' => 'You are not authorized to log in as ' . ucfirst($request->role) . '.']);
    }

    Auth::login($user);
    $request->session()->regenerate();

    // Redirect based on role
    if ($user->role === 'Admin') {
        return redirect()->route('admin.dashboard')->with('success', 'Welcome, Admin!');
    } elseif ($user->role === 'Employee') {
        return redirect()->route('employee.dashboard')->with('success', 'Welcome, Employee!');
    } elseif ($user->role === 'HR') {
        return redirect()->route('hr.dashboard')->with('success', 'Welcome, HR!');
    }

    return redirect()->route('home');
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

        return redirect()->route('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the selection page
        return redirect()->route('home');
    }

        public function index()
    {
        return view('pds.index');
    }



}
