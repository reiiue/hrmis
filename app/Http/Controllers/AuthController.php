<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login page based on role (Admin, HR, or Employee)
     */
    public function showLogin($role = null)
    {
        if ($role !== null && !in_array(strtolower($role), ['admin', 'employee', 'hr'])) {
            abort(404);
        }

        return view('auth.login', ['role' => $role]);
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'role' => 'nullable|string'
        ]);

        $credentials = $request->only('email', 'password');

        // Attempt login
        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Invalid email or password.']);
        }

        // Regenerate session to prevent fixation
        $request->session()->regenerate();

        $user = Auth::user();

        // Check account status
        if ($user->status !== 'Active') {
            Auth::logout();
            return back()->withErrors(['email' => 'Account is ' . $user->status . '.']);
        }

        // Check role if specified
        if ($request->filled('role') && strtolower($user->role) !== strtolower($request->role)) {
            Auth::logout();
            return back()->withErrors(['email' => 'You are not authorized to log in as ' . ucfirst($request->role) . '.']);
        }

        // Redirect based on role
        return match ($user->role) {
            'Admin' => redirect()->route('admin.dashboard')->with('success', 'Welcome, Admin!'),
            'HR' => redirect()->route('hr.dashboard')->with('success', 'Welcome, HR!'),
            'Employee' => redirect()->route('employee.dashboard')->with('success', 'Welcome, Employee!'),
            default => redirect()->route('home'),
        };
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed',
            'role' => 'required|in:Admin,HR,Employee',
        ]);

        User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'Active',
        ]);

        return redirect()->route('login', strtolower($request->role))
                         ->with('success', 'Registration successful! You can now log in.');
    }

    /**
     * Logout the user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }

    /**
     * Temporary placeholder (optional)
     */
    public function index()
    {
        return view('pds.index');
    }
}
