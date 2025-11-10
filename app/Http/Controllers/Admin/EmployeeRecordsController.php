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

    // Get checkbox arrays, default to empty array if not set
    $pdsStatuses = $request->query('pds_status', []); 
    $salnStatuses = $request->query('saln_status', []);

    $employees = User::with(['personalInformation', 'pds', 'saln'])
        ->where('role', 'Employee')
        ->when($search, function($q) use ($search) {
            $q->whereHas('personalInformation', function($q2) use ($search) {
                $q2->where('first_name', 'like', "%{$search}%")
                   ->orWhere('last_name', 'like', "%{$search}%")
                   ->orWhere('position', 'like', "%{$search}%");
            });
        })
        ->when($pdsStatuses, function($q) use ($pdsStatuses) {
            $q->where(function($q2) use ($pdsStatuses) {
                if (in_array('filed', $pdsStatuses)) {
                    $q2->orWhereHas('pds');
                }
                if (in_array('not_filed', $pdsStatuses)) {
                    $q2->orWhereDoesntHave('pds');
                }
            });
        })
        ->when($salnStatuses, function($q) use ($salnStatuses) {
            $q->where(function($q2) use ($salnStatuses) {
                if (in_array('filed', $salnStatuses)) {
                    $q2->orWhereHas('saln');
                }
                if (in_array('not_filed', $salnStatuses)) {
                    $q2->orWhereDoesntHave('saln');
                }
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10)
        ->withQueryString(); // keeps search and filters in pagination links

    return view('admin.employee_records.index', compact('employees'));
}


       // Handle PDS approve/reject
    public function pdsAction(Request $request, $id)
    {
        $request->validate(['action' => 'required|in:approve,reject']);
        $user = User::with('pds')->findOrFail($id);

        if ($user->pds) {
            $user->pds->status = $request->action === 'approve' ? 'approved' : 'rejected';
            $user->pds->save();
        }

        return redirect()->back()->with('success', 'PDS has been ' . ($request->action === 'approve' ? 'approved' : 'rejected'));
    }

    // Handle SALN approve/reject
    public function salnAction(Request $request, $id)
    {
        $request->validate(['action' => 'required|in:approve,reject']);
        $user = User::with('saln')->findOrFail($id);

        if ($user->saln) {
            $user->saln->status = $request->action === 'approve' ? 'verified' : 'flagged';
            $user->saln->save();
        }

        return redirect()->back()->with('success', 'SALN has been ' . ($request->action === 'approve' ? 'verified' : 'flagged'));
    }


}
