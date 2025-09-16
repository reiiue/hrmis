<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PersonalInformation;
use App\Models\Address;
use App\Models\Agency;
use App\Models\Spouse;
use App\Models\Child;
use App\Models\AssetsRealProperty;
use App\Models\AssetsPersonalProperty;
use App\Models\TotalCosts; // ✅ Import the model

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

        $spouse = $personalInfo?->spouse()->first();

        $spouseAgency = $personalInfo
            ? Agency::where('personal_information_id', $personalInfo->id)
                ->where('type', 'spouse')
                ->first()
            : null;

        $children = $personalInfo?->children()->get() ?? collect();

        // ✅ Fetch real properties
        $real_properties = $personalInfo?->assetsRealProperties()->get() ?? collect();
        $personal_properties = $personalInfo?->assetsPersonalProperties()->get() ?? collect();

        // ✅ Fetch total costs (if exists)
        $total_costs = $personalInfo->totalCosts()->latest()->first(); 

        return view('saln.index', compact(
            'personalInfo',
            'permanentAddress',
            'agency',
            'spouse',
            'spouseAgency',
            'children',
            'real_properties',
            'personal_properties',
            'total_costs'
        ));
    }

    /**
     * Store or update SALN information.
     */
    public function update(Request $request)
    {
        // --- Clean comma-formatted decimals before validation ---
        $input = $request->all();

        $decimalFields = ['assessed_value', 'current_fair_market_value', 'acquisition_cost'];
        foreach ($decimalFields as $field) {
            if (isset($input[$field])) {
                $input[$field] = array_map(fn($v) => $v !== null ? str_replace(',', '', $v) : null, $input[$field]);
            }
        }

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

            // ✅ Real Properties
            'description.*' => 'required|string|max:255',
            'kind.*'        => 'required|string|max:255',
            'location.*'    => 'required|string|max:500',
            'assessed_value.*' => 'required|numeric',
            'current_fair_market_value.*' => 'required|numeric',
            'acquisition_year.*' => 'required|digits:4',
            'acquisition_mode.*' => 'nullable|string|max:255',
            'acquisition_cost.*' => 'nullable|numeric',

            // ✅ Children
            'children.*.full_name'             => 'nullable|string|max:255',
            'children.*.date_of_birth'         => 'nullable|date',
            'children.*.is_living_with_declarant' => 'nullable|boolean',

            // ✅ Personal Properties
            'description_personal.*' => 'required|string|max:500',
            'year_acquired_personal.*' => 'nullable|string|max:10',
            'acquisition_cost_personal.*' => 'required|numeric',
        ]);

        $user = Auth::user();

        $personalInfo = $user->personalInformation()->updateOrCreate(
            [],
            [
                'position'     => $request->position,
                'filing_type'  => $request->filing_type,
            ]
        );

        // --- Agencies & Spouse ---
        if ($request->filled('agency_name') || $request->filled('agency_address')) {
            Agency::updateOrCreate(
                ['personal_information_id' => $personalInfo->id, 'type' => 'declarant'],
                ['name' => $request->agency_name, 'address' => $request->agency_address]
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
                ['personal_information_id' => $personalInfo->id, 'type' => 'spouse'],
                ['name' => $request->spouse_agency_name, 'address' => $request->spouse_agency_address]
            );
        }

        // --- Children ---
        if ($request->has('children')) {
            foreach ($request->children as $key => $childData) {
                if (str_starts_with($key, 'new_')) {
                    $personalInfo->children()->create([
                        'full_name' => $childData['full_name'] ?? null,
                        'date_of_birth' => $childData['date_of_birth'] ?? null,
                        'is_living_with_declarant' => isset($childData['is_living_with_declarant']),
                    ]);
                } else {
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

        if ($request->has('deleted_children')) {
            $personalInfo->children()->whereIn('id', $request->deleted_children)->delete();
        }

        // --- Real Properties ---
        $realIdsFromForm = $request->input('real_property_id', []);
        $descriptions    = $request->input('description', []);

        $processedIds = [];

        foreach ($descriptions as $index => $desc) {
            if ($desc) {
                $real = AssetsRealProperty::updateOrCreate(
                    [
                        'id' => $realIdsFromForm[$index] ?? null,
                        'personal_information_id' => $personalInfo->id,
                    ],
                    [
                        'description'               => $desc,
                        'kind'                      => $request->kind[$index] ?? null,
                        'location'                  => $request->location[$index] ?? null,
                        'assessed_value'            => $request->assessed_value[$index] ?? null,
                        'current_fair_market_value' => $request->current_fair_market_value[$index] ?? null,
                        'acquisition_year'          => $request->acquisition_year[$index] ?? null,
                        'acquisition_mode'          => $request->acquisition_mode[$index] ?? null,
                        'acquisition_cost'          => $request->acquisition_cost[$index] ?? null,
                    ]
                );

                $processedIds[] = $real->id;
            }
        }

        AssetsRealProperty::where('personal_information_id', $personalInfo->id)
            ->whereNotIn('id', $processedIds)
            ->delete();
        
                /**
         * --- Save Personal Properties ---
         */
        $personalIdsFromForm = $request->input('personal_property_id', []);
        $personalDescs       = $request->input('description_personal', []);
        $processedPersonalIds = [];

        foreach ($personalDescs as $index => $desc) {
            if ($desc) {
                $personal = AssetsPersonalProperty::updateOrCreate(
                    [
                        'id' => $personalIdsFromForm[$index] ?? null,
                        'personal_information_id' => $personalInfo->id,
                    ],
                    [
                        'description'      => $desc,
                        'year_acquired'    => $request->year_acquired_personal[$index] ?? null,
                        'acquisition_cost' => $request->acquisition_cost_personal[$index] ?? null,
                    ]
                );

                $processedPersonalIds[] = $personal->id;
            }
        }

        AssetsPersonalProperty::where('personal_information_id', $personalInfo->id)
            ->whereNotIn('id', $processedPersonalIds)
            ->delete();


        // ✅ --- Calculate & Save Total Costs ---
        $realPropertiesTotal = AssetsRealProperty::where('personal_information_id', $personalInfo->id)
            ->sum('acquisition_cost');

        $personalPropertiesTotal = AssetsPersonalProperty::where('personal_information_id', $personalInfo->id)
            ->sum('acquisition_cost');

        $totalAssets = $realPropertiesTotal + $personalPropertiesTotal;
        $totalLiabilities = 0; // later update when you add liabilities
        $netWorth = $totalAssets - $totalLiabilities;

        TotalCosts::updateOrCreate(
            ['personal_information_id' => $personalInfo->id],
            [
                'real_properties_total'   => $realPropertiesTotal,
                'personal_property_total' => $personalPropertiesTotal,
                'total_assets_costs'      => $totalAssets,
                'total_liabilities'       => $totalLiabilities,
                'net_worth'               => $netWorth,
            ]
        );


        return redirect()->route('saln.index')->with('success', 'SALN saved successfully!');
    }
}
