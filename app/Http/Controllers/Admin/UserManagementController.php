<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Display a paginated list of users.
     */
    public function index(Request $request)
    {
        // Basic search + eager load personalInformation and paginate
        $query = User::with('personalInformation')->orderBy('created_at', 'desc');

        if ($search = $request->query('search')) {
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%") // changed from username to email
                ->orWhere('role', 'like', "%{$search}%")
                ->orWhereHas('personalInformation', function($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            });
        }

        // adjust per-page number as desired
        $users = $query->paginate(10);

        // pass the variable to the view
        return view('admin.user_management.index', compact('users'));
    }


    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.user_management.create');
    }

    /**
     * Store a newly created user in storage.
     */
public function store(Request $request)
{
    // Validate the form inputs
    $request->validate([
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed', // 'confirmed'
        'role' => 'required|in:Admin,HR,Employee',
        'agency_employee_no' => 'required|string|unique:personal_informations,agency_employee_no',
        'department' => 'required|string',
        'last_name' => 'required|string',
        'first_name' => 'required|string',
        'middle_name' => 'required|string|max:1',
    ]);

    // Create the user
    $user = User::create([
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'status' => $request->status ?? 'Active',
    ]);

    // Create personal information linked to the user
    $user->personalInformation()->create([
        'agency_employee_no' => $request->agency_employee_no,
        'department' => $request->department,
        'last_name' => $request->last_name,
        'first_name' => $request->first_name,
        'middle_name' => $request->middle_name,
        'suffix' => $request->suffix,
    ]);

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'User created successfully.');
}


    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.user_management.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */

public function update(Request $request, User $user)
{
    // Validate input
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:1',
        'suffix' => 'nullable|string|max:10',
        'department' => 'nullable|string|max:255',
        'agency_employee_no' => 'nullable|string|max:50',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required|in:Admin,HR,Employee',
        'status' => 'required|in:Active,Inactive,Suspended,Pending,Archived',
        'password' => 'nullable|string|min:6|confirmed',
    ]);

    // Update User fields
    $user->update([
        'email' => $request->email,
        'role' => $request->role,
        'status' => $request->status,
    ]);

    // Update password if provided
    if ($request->filled('password')) {
        $user->update(['password' => Hash::make($request->password)]);
    }

    // Update related PersonalInformation
    $user->personalInformation()->updateOrCreate(
        ['user_id' => $user->id],
        [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'suffix' => $request->suffix,
            'department' => $request->department,
            'agency_employee_no' => $request->agency_employee_no,
        ]
    );

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'User updated successfully.');
}


    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
