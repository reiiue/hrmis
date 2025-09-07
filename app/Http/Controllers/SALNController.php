<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PersonalInformation;
use App\Models\Address;
use App\Models\Agency;
use App\Models\Spouse;

class SALNController extends Controller
{
    /**
     * Show the SALN form.
     */
    public function index()
    {
        $user = Auth::user();
        $personalInfo = PersonalInformation::where('user_id', $user->id)->first();

        $permanentAddress = $personalInfo
            ? Address::where('personal_information_id', $personalInfo->id)
                ->where('address_type', 'permanent')
                ->first()
            : null;

        $agency = $personalInfo
            ? Agency::where('personal_information_id', $personalInfo->id)
                ->where('type', 'declarant')
                ->first()
            : null;

        $spouse = $personalInfo
            ? $personalInfo->spouse()->first()
            : null;

        $spouseAgency = $personalInfo
            ? Agency::where('personal_information_id', $personalInfo->id)
                ->where('type', 'spouse')
                ->first()
            : null;

        return view('saln.index', compact(
            'personalInfo',
            'permanentAddress',
            'agency',
            'spouse',
            'spouseAgency'
        ));
    }

    /**
     * Store or update SALN information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'filing_type'           => 'nullable|in:joint,separate,not_applicable',
            'position'              => 'required|string|max:255',
            'agency_name'           => 'nullable|string|max:255',
            'agency_address'        => 'nullable|string|max:500',
            'spouse_first'          => 'nullable|string|max:255',
            'spouse_last'           => 'nullable|string|max:255',
            'spouse_mi'             => 'nullable|string|max:10',
            'spouse_position'       => 'nullable|string|max:255',
            'spouse_agency_name'    => 'nullable|string|max:255',
            'spouse_agency_address' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        $personalInfo = $user->personalInformation()->updateOrCreate(
            [],
            [
                        'position' => $request->position,
                        'filing_type'  => $request->filing_type, // ✅ new field
                    ]
            
        );

        if ($request->filled('agency_name') || $request->filled('agency_address')) {
            Agency::updateOrCreate(
                [
                    'personal_information_id' => $personalInfo->id,
                    'type' => 'declarant',
                ],
                [
                    'name'    => $request->agency_name,
                    'address' => $request->agency_address,
                ]
            );
        }

        if (
            $request->filled('spouse_first') ||
            $request->filled('spouse_last') ||
            $request->filled('spouse_mi') ||
            $request->filled('spouse_position')
        ) {
            Spouse::updateOrCreate(
                ['personal_information_id' => $personalInfo->id],
                [
                    'first_name'  => $request->spouse_first,
                    'last_name'   => $request->spouse_last,
                    'middle_name' => $request->spouse_mi,
                    'position'    => $request->spouse_position,
                ]
            );
        }

        if ($request->filled('spouse_agency_name') || $request->filled('spouse_agency_address')) {
            Agency::updateOrCreate(
                [
                    'personal_information_id' => $personalInfo->id,
                    'type' => 'spouse',
                ],
                [
                    'name'    => $request->spouse_agency_name,
                    'address' => $request->spouse_agency_address,
                ]
            );
        }

        return redirect()->route('saln.index')->with('success', 'SALN saved successfully!');
    }

    // ... other methods ...
}
