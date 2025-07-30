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

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'middle_name' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date|max:100',
            'place_of_birth' => 'required|string|max:255',
        ]);

        $user->personalInformation()->updateOrCreate([], [
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'suffix' => $request->suffix,
            'middle_name' => $request->middle_name,
            'date_of_birth' => $request->date_of_birth,
            'place_of_birth' => $request->place_of_birth,
        ]);

        return redirect()->route('pds.index')->with('success', 'Personal Information updated successfully.');
    }


}
