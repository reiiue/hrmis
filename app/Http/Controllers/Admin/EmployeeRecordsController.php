<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class EmployeeRecordsController extends Controller
{
    /**
     * Display all employees with PDS and SALN statuses
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $employees = User::with(['personalInformation', 'pds', 'saln'])
            ->when($search, function($q) use ($search) {
                $q->whereHas('personalInformation', function($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('position', 'like', "%{$search}%");
                });
            })
            ->where('role', 'Employee')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.employee_records.index', compact('employees'));
    }


}
