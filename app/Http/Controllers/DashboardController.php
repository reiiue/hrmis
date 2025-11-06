<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Submission;

class DashboardController extends Controller
{
    // Admin Dashboard
    public function admin()
    {
        // Fetch all submissions
        $submissions = Submission::with(['sender.personalInformation', 'recipient.personalInformation'])
            ->orderBy('created_at', 'desc')
            ->take(5) // still show only 5 latest on dashboard
            ->get();

        return view('dashboards.admin_dashboard', compact('submissions'));
    }


// HR Dashboard
public function hr()
{
    $user = auth()->user();

    // Make sure the logged-in user is HR
    if ($user->role !== 'HR') {
        abort(403, 'Unauthorized access');
    }

    // Get submissions where the recipient is the logged-in HR
    $submissions = Submission::with(['sender.personalInformation', 'recipient.personalInformation'])
        ->where('recipient_id', $user->id) // Filter by this HR
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    return view('dashboards.hr_dashboard', compact('submissions'));
}





    // Employee Dashboard
    public function employee()
    {
        return view('dashboards.employee_dashboard');
    }
}
