<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalInformation;
use Illuminate\Support\Facades\Auth;
use App\Models\Country;
use App\Models\Address;
use App\Models\GovernmentId; // <-- Add this

class PDSController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $personalInfo = PersonalInformation::where('user_id', $user->id)->first();
        $countries = Country::orderBy('name')->get();

        $residentialAddress = null;
        $permanentAddress = null;
        $governmentIds = null; // <-- Add this

        if ($personalInfo) {
            $residentialAddress = $personalInfo->addresses()
                ->where('address_type', 'Residential')
                ->first();

            $permanentAddress = $personalInfo->addresses()
                ->where('address_type', 'Permanent')
                ->first();

            $governmentIds = $personalInfo->governmentIds()->first(); // <-- Get IDs
        }

        return view('pds.index', compact(
            'personalInfo',
            'countries',
            'residentialAddress',
            'permanentAddress',
            'governmentIds'
        ));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            // Personal Information
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
            'blood_type' => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'telephone_no' => 'nullable|string|max:20',
            'mobile_no' => 'nullable|string|max:20',
            'agency_employee_no' => 'nullable|string|max:50',
            'email' => 'nullable|string|max:255',

            //Government IDs
            'gsis_id' => 'nullable|string|max:50',
            'pagibig_id' => 'nullable|string|max:50', 
            'philhealth_id' => 'nullable|string|max:50',
            'sss_id' => 'nullable|string|max:50',
            'tin_no' => 'nullable|string|max:20',

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
            'blood_type',
            `telephone_no`,
            'mobile_no',
            'agency_employee_no',
            'email',
        ]);

        if ($request->citizenship === 'Dual Citizenship') {
            $data['dual_citizenship_type'] = $request->dual_citizenship_type;
            $data['dual_citizenship_country_id'] = $request->dual_citizenship_country_id;
        } else {
            $data['dual_citizenship_type'] = null;
            $data['dual_citizenship_country_id'] = null;
        }

        foreach ($data as $key => $value) {
            $data[$key] = $value === '' ? null : $value;
        }

        $user->personalInformation()->updateOrCreate([], $data);
        $personalInformation = auth()->user()->personalInformation;

        // --- Residential Address ---
        if ($residential = $request->input('address.residential')) {
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
                    'zip_code' => $residential['zip_code'] ?? null,
                ]
            );
        }

        // --- Permanent Address ---
        if ($permanent = $request->input('address.permanent')) {
            Address::updateOrCreate(
                [
                    'personal_information_id' => $personalInformation->id,
                    'address_type' => 'Permanent',
                ],
                [
                    'house_block_lot_no' => $permanent['house_block_lot_no'] ?? null,
                    'street' => $permanent['street'] ?? null,
                    'subdivision' => $permanent['subdivision'] ?? null,
                    'barangay' => $permanent['barangay'] ?? null,
                    'city' => $permanent['city'] ?? null,
                    'province' => $permanent['province'] ?? null,
                    'zip_code' => $permanent['zip_code'] ?? null,
                ]
            );
        }

        // --- Government IDs ---
        GovernmentId::updateOrCreate(
            [
                'personal_information_id' => $personalInformation->id,
            ],
            [
                'gsis_id' => $request->input('gsis_id') ?? null,
                'pagibig_id' => $request->input('pagibig_id') ?? null,
                'philhealth_id' => $request->input('philhealth_id') ?? null,
                'sss_id' => $request->input('sss_id') ?? null,
                'tin_id' => $request->input('tin_id') ?? null,
            ]
        );

        return redirect()->route('pds.index')->with('success', 'Personal Information updated successfully.');
    }
}
