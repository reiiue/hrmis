<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PersonalInformation;
use App\Models\Address;
use App\Models\Agency;
use App\Models\Spouse;
use App\Models\Child; // ✅ Make sure you have this model

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

        // ✅ Fetch children
        $children = $personalInfo
            ? $personalInfo->children()->get()
            : collect();

        return view('saln.index', compact(
            'personalInfo',
            'permanentAddress',
            'agency',
            'spouse',
            'spouseAgency',
            'children'
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
            // ✅ Add children validation
            'children.*.full_name'  => 'nullable|string|max:255',
            'children.*.date_of_birth' => 'nullable|date',
            'children.*.is_living_with_declarant' => 'nullable|boolean',
        ]);

        $user = Auth::user();

        $personalInfo = $user->personalInformation()->updateOrCreate(
            [],
            [
                'position' => $request->position,
                'filing_type'  => $request->filing_type,
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

        // Update existing or create new children
        if ($request->has('children')) {
            foreach ($request->children as $key => $childData) {
                if (str_starts_with($key, 'new_')) {
                    // New child → create
                    $personalInfo->children()->create([
                        'full_name' => $childData['full_name'] ?? null,
                        'date_of_birth' => $childData['date_of_birth'] ?? null,
                        'is_living_with_declarant' => isset($childData['is_living_with_declarant']),
                    ]);
                } else {
                    // Existing child → update
                    $child = $personalInfo->children()->find($key);
                    if ($child) {
                        $child->update([
                            'full_name' => $childData['full_name'] ?? $child->full_name,
                            'date_of_birth' => $childData['date_of_birth'] ?? $child->date_of_birth,
                            'is_living_with_declarant' => isset($childData['is_living_with_declarant']),
                        ]);
                    }
                }
            }
        }

        // Delete children if marked
        if ($request->has('deleted_children')) {
            $personalInfo->children()->whereIn('id', $request->deleted_children)->delete();
        }


        return redirect()->route('saln.index')->with('success', 'SALN saved successfully!');
    }
}
