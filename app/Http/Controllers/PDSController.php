<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalInformation;
use Illuminate\Support\Facades\Auth;
use App\Models\Country;
use App\Models\Address;

class PDSController extends Controller
{
    // Show the PDS form
    public function index()
    {
    $user = Auth::user();
    $personalInfo = PersonalInformation::where('user_id', $user->id)->first();
    $countries = Country::orderBy('name')->get();

    $residentialAddress = null;
    if ($personalInfo) {
        $residentialAddress = $personalInfo->addresses()
            ->where('address_type', 'Residential')
            ->first();
    }

    return view('pds.index', compact('personalInfo', 'countries', 'residentialAddress'));
    }

    // Handle form submission
public function update(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'last_name' => 'nullable|string|max:255',
        'first_name' => 'nullable|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'suffix' => 'nullable|string|max:255',
        'date_of_birth' => 'nullable|date',
        'place_of_birth' => 'nullable|string|max:255',
        'sex' => 'nullable|in:Male,Female',
        'citizenship' => 'nullable|string|in:Filipino,Dual Citizenship',
        'dual_citizenship_type' => 'nullable|string|in:By Birth,By Naturalization',
        'dual_citizenship_country_id' => 'nullable|exists:countries,id',
        'civil_status' => 'nullable|in:Single,Married,Widowed,Separated,Others',
        'height' => 'nullable|numeric|min:0|max:300',
        'weight' => 'nullable|numeric|min:0|max:500',
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
        'civil_status',
        'height',
        'weight',
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

    // Get the authenticated user's personal info
    $personalInformation = auth()->user()->personalInformation;

    // --- Handle Residential Address ---
    $residential = $request->input('address.residential');

    if ($residential) {
        Address::updateOrCreate(
            [
                'personal_information_id' => $personalInformation->id,
                'address_type' => 'Residential',
            ],
            [
                'house_block_lot_no' => $residential['house_block_lot_no'] ?? null,
                'street' => $residential['street'] ?? null,
                'subdivision' => $residential['subdivision'] ?? null,
                'barangay' => $residential['barangay'] ?? null,
                'city' => $residential['city'] ?? null,
                'province' => $residential['province'] ?? null,
            ]
        );
    }

    return redirect()->route('pds.index')->with('success', 'Personal Information updated successfully.');
}


}
