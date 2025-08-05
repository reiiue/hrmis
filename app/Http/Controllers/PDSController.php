<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalInformation;
use Illuminate\Support\Facades\Auth;
use App\Models\Country;

class PDSController extends Controller
{
    // Show the PDS form
    public function index()
    {
        $user = Auth::user();
        $personalInfo = PersonalInformation::where('user_id', $user->id)->first();
        $countries = Country::orderBy('name')->get();

        return view('pds.index', compact('personalInfo', 'countries'));
    }

    // Handle form submission
public function update(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'last_name' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'middle_name' => 'required|string|max:255',
        'suffix' => 'nullable|string|max:255',
        'date_of_birth' => 'required|date',
        'place_of_birth' => 'required|string|max:255',
        'sex' => 'required|in:Male,Female',
        'citizenship' => 'required|string|in:Filipino,Dual Citizenship',
        'dual_citizenship_type' => 'nullable|string|in:By Birth,By Naturalization',
        'dual_citizenship_country_id' => 'nullable|exists:countries,id',
    ]);

    $data = $request->only([
        'last_name', 
        'first_name',
        'middle_name',
        'suffix',
        'date_of_birth',
        'place_of_birth',
        'sex',
        'citizenship',
    ]);

    // Add dual citizenship fields only if applicable
    if ($request->citizenship === 'Dual Citizenship') {
        $data['dual_citizenship_type'] = $request->dual_citizenship_type;
        $data['dual_citizenship_country_id'] = $request->dual_citizenship_country_id;
    } else {
        $data['dual_citizenship_type'] = null;
        $data['dual_citizenship_country_id'] = null;
    }

    // Convert empty string to null (so "no data" is saved as NULL)
    foreach ($data as $key => $value) {
        $data[$key] = $value === '' ? null : $value;
    }

    // Update or create personal information
    $user->personalInformation()->updateOrCreate([], $data);

    return redirect()->route('pds.index')->with('success', 'Personal Information updated successfully.');
}


}
