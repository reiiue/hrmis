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
use App\Models\Liability;
use App\Models\TotalCosts;
use App\Models\BusinessInterest;
use App\Models\RelativeInGovService;
use App\Models\SalnCertification;


class SALNController extends Controller
{
    /**
     * Show the SALN form.
     */
    public function index()
    {
        $user = Auth::user();
        $personalInfo = PersonalInformation::where('user_id', $user->id)->first();

        $permanentAddress = $personalInfo?->addresses()
            ->where('address_type', 'permanent')
            ->first();

        $agency = $personalInfo?->agencies()
            ->where('type', 'personal')
            ->first();

        $spouse = $personalInfo?->spouse()->first();

        $spouseAgency = $personalInfo?->agencies()
            ->where('type', 'spouse')
            ->first();

        $children = $personalInfo?->children ?? collect();

        // ✅ Reporting year
        $reportingYear = now()->year;

        // ✅ Assets
        $real_properties = $personalInfo?->assetsRealProperties()
            ->where('reporting_year', $reportingYear)
            ->get() ?? collect();

        $personal_properties = $personalInfo?->assetsPersonalProperties()
            ->where('reporting_year', $reportingYear)
            ->get() ?? collect();

        // ✅ Liabilities
        $liabilities = $personalInfo?->liabilities()
            ->where('reporting_year', $reportingYear)
            ->get() ?? collect();

        // ✅ Totals
        $total_costs = $personalInfo?->totalCosts()
            ->where('reporting_year', $reportingYear)
            ->latest()
            ->first();
        
        $business_interests = $personalInfo?->businessInterests()
        ->whereYear('date_of_acquisition', '<=', $reportingYear)
        ->get() ?? collect();

        $no_business_interest = $personalInfo?->businessInterests()
        ->where('no_business_interest', true)
        ->exists();
        
        $saln_certification = SalnCertification::where('personal_information_id', $personalInfo->id)->first();

        // ✅ Relatives in Government Service
        $relatives_in_gov_service = RelativeInGovService::where('personal_information_id', $personalInfo->id)->get();
        $no_relative_in_gov_service = $relatives_in_gov_service->where('no_relative_in_gov_service', true)->isNotEmpty();

        return view('saln.index', compact(
            'personalInfo',
            'permanentAddress',
            'agency',
            'spouse',
            'spouseAgency',
            'children',
            'real_properties',
            'personal_properties',
            'liabilities',
            'total_costs',
            'reportingYear',
            'business_interests',
            'no_business_interest',
            'relatives_in_gov_service',
            'no_relative_in_gov_service',
            'saln_certification'
        ));
    }

    /**
     * Store or update SALN information.
     */
    public function update(Request $request)
    {
        $reportingYear = now()->year;

        // --- Clean comma-formatted decimals ---
        $input = $request->all();
        $decimalFields = ['assessed_value', 'current_fair_market_value', 'acquisition_cost', 'acquisition_cost_personal', 'outstanding_balance'];
        foreach ($decimalFields as $field) {
            if (isset($input[$field])) {
                $input[$field] = array_map(fn($v) => $v !== null ? str_replace(',', '', $v) : null, $input[$field]);
            }
        }

        // --- Validation ---
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

            // ✅ Liabilities
            'nature_type.*'         => 'required|string|max:255',
            'name_of_creditors.*'   => 'required|string|max:255',
            'outstanding_balance.*' => 'required|numeric',

            // ✅ Business Interests
            'business_interest_id.*'       => 'nullable|integer|exists:business_interests,id',
            'name_of_business.*'           => 'nullable|string|max:255',
            'business_address.*'           => 'nullable|string|max:500',
            'name_of_business_interest.*'  => 'nullable|string|max:255',
            'date_of_acquisition.*'        => 'nullable|date',
            'no_business_interest'         => 'nullable|boolean',

            // ✅ Relatives in Government
            'relative_id.*'        => 'nullable|integer|exists:relatives_in_gov_service,id',
            'name_of_relative.*'   => 'nullable|string|max:255',
            'relationship.*'       => 'nullable|string|max:255',
            'position_of_relative.*'  => 'nullable|string|max:255',
            'name_of_agency.*'     => 'nullable|string|max:255',
            'no_relative_in_gov_service' => 'nullable|boolean',

            // ✅ Certification Fields
            'government_issued_id' => 'nullable|string|max:255',
            'id_no' => 'nullable|string|max:255',
            'date_issued' => 'nullable|date',
            'place_issued' => 'nullable|string|max:255',
            'signature' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
                ['personal_information_id' => $personalInfo->id, 'type' => 'personal'],
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
                        'reporting_year'            => $reportingYear,
                    ]
                );

                $processedIds[] = $real->id;
            }
        }

        AssetsRealProperty::where('personal_information_id', $personalInfo->id)
            ->whereNotIn('id', $processedIds)
            ->where('reporting_year', $reportingYear)
            ->delete();

        // --- Personal Properties ---
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
                        'reporting_year'   => $reportingYear,
                    ]
                );

                $processedPersonalIds[] = $personal->id;
            }
        }

        AssetsPersonalProperty::where('personal_information_id', $personalInfo->id)
            ->whereNotIn('id', $processedPersonalIds)
            ->where('reporting_year', $reportingYear)
            ->delete();

        // --- Liabilities ---
        $liabilityIdsFromForm = $request->input('liability_id', []);
        $natureTypes          = $request->input('nature_type', []);
        $processedLiabilityIds = [];

        foreach ($natureTypes as $index => $nature) {
            if ($nature) {
                $liability = Liability::updateOrCreate(
                    [
                        'id' => $liabilityIdsFromForm[$index] ?? null,
                        'personal_information_id' => $personalInfo->id,
                    ],
                    [
                        'nature_type'        => $nature,
                        'name_of_creditors'  => $request->name_of_creditors[$index] ?? null,
                        'outstanding_balance'=> $request->outstanding_balance[$index] ?? null,
                        'reporting_year'     => $reportingYear,
                    ]
                );

                $processedLiabilityIds[] = $liability->id;
            }
        }

        Liability::where('personal_information_id', $personalInfo->id)
            ->whereNotIn('id', $processedLiabilityIds)
            ->where('reporting_year', $reportingYear)
            ->delete();

        // ✅ --- Calculate & Save Total Costs ---
        $realPropertiesTotal = AssetsRealProperty::where('personal_information_id', $personalInfo->id)
            ->where('reporting_year', $reportingYear)
            ->sum('acquisition_cost');

        $personalPropertiesTotal = AssetsPersonalProperty::where('personal_information_id', $personalInfo->id)
            ->where('reporting_year', $reportingYear)
            ->sum('acquisition_cost');

        $liabilitiesTotal = Liability::where('personal_information_id', $personalInfo->id)
            ->where('reporting_year', $reportingYear)
            ->sum('outstanding_balance');

        $totalAssets = $realPropertiesTotal + $personalPropertiesTotal;
        $netWorth = $totalAssets - $liabilitiesTotal;

        TotalCosts::updateOrCreate(
            [
                'personal_information_id' => $personalInfo->id,
                'reporting_year'          => $reportingYear,
            ],
            [
                'real_properties_total'   => $realPropertiesTotal,
                'personal_property_total' => $personalPropertiesTotal,
                'total_assets_costs'      => $totalAssets,
                'total_liabilities'       => $liabilitiesTotal,
                'net_worth'               => $netWorth,
            ]
        );
        
        // --- Business Interests ---
        $businessIdsFromForm  = $request->input('business_interest_id', []);
        $businessNames        = $request->input('name_of_business', []);
        $processedBusinessIds = [];

        foreach ($businessNames as $index => $name) {
            if ($name) {
                $business = BusinessInterest::updateOrCreate(
                    [
                        'id' => $businessIdsFromForm[$index] ?? null,
                        'personal_information_id' => $personalInfo->id,
                        'reporting_year' => $reportingYear,
                    ],
                    [
                        'name_of_business'          => $name,
                        'business_address'          => $request->business_address[$index] ?? null,
                        'name_of_business_interest' => $request->name_of_business_interest[$index] ?? null,
                        'date_of_acquisition'       => $request->date_of_acquisition[$index] ?? null,
                        'no_business_interest'      => false,
                        'reporting_year'            => $reportingYear,
                    ]
                );

                $processedBusinessIds[] = $business->id;
            }
        }

        // ✅ Handle "No Business Interest" checkbox
        if ($request->boolean('no_business_interest')) {
            $business = BusinessInterest::updateOrCreate(
                [
                    'personal_information_id' => $personalInfo->id,
                    'reporting_year'           => $reportingYear,
                    'no_business_interest'     => true,
                ],
                [
                    'name_of_business'          => null,
                    'business_address'          => null,
                    'name_of_business_interest' => null,
                    'date_of_acquisition'       => null,
                    'no_business_interest'      => true,
                    'reporting_year'            => $reportingYear,
                ]
            );

            $processedBusinessIds[] = $business->id;
        }

        // ✅ Clean up removed records
        BusinessInterest::where('personal_information_id', $personalInfo->id)
            ->where('reporting_year', $reportingYear)
            ->whereNotIn('id', $processedBusinessIds)
            ->delete();

            

if ($request->boolean('no_relative_in_gov_service')) {
    RelativeInGovService::where('personal_information_id', $personalInfo->id)->delete();
    RelativeInGovService::create([
        'personal_information_id'     => $personalInfo->id,
        'no_relative_in_gov_service'  => true,
        'reporting_year'              => $request->input('reporting_year', date('Y')), // ✅ add this
    ]);
} else {
    $relativeIds   = $request->relative_id ?? [];
    $names         = $request->name_of_relative ?? [];
    $relationships = $request->relationship ?? [];
    $positions     = $request->position_of_relative ?? [];
    $agencies      = $request->name_of_agency ?? [];

    $processedRelativeIds = [];

    foreach ($names as $i => $name) {
        if (empty($name) && empty($relationships[$i]) && empty($positions[$i]) && empty($agencies[$i])) {
            continue;
        }

        if (!empty($relativeIds[$i])) {
            $relative = RelativeInGovService::find($relativeIds[$i]);
            if ($relative) {
                $relative->update([
                    'name_of_relative'           => $name,
                    'relationship'               => $relationships[$i] ?? null,
                    'position_of_relative'       => $positions[$i] ?? null,
                    'name_of_agency'             => $agencies[$i] ?? null,
                    'no_relative_in_gov_service' => false,
                    'reporting_year'             => $request->input('reporting_year', date('Y')), // ✅ add this
                ]);
                $processedRelativeIds[] = $relative->id;
            }
        } else {
            $relative = RelativeInGovService::create([
                'personal_information_id'     => $personalInfo->id,
                'name_of_relative'            => $name,
                'relationship'                => $relationships[$i] ?? null,
                'position_of_relative'        => $positions[$i] ?? null,
                'name_of_agency'              => $agencies[$i] ?? null,
                'no_relative_in_gov_service'  => false,
                'reporting_year'              => $request->input('reporting_year', date('Y')), // ✅ add this
            ]);
            $processedRelativeIds[] = $relative->id;
        }
    }

    RelativeInGovService::where('personal_information_id', $personalInfo->id)
        ->whereNotIn('id', $processedRelativeIds)
        ->delete();
}

        /**
         * ==================================================
         * ✅ SALN CERTIFICATION SECTION
         * ==================================================
         */
        $certificationData = [
            'government_issued_id' => $request->government_issued_id,
            'id_no' => $request->id_no,
            'date_issued' => $request->date_issued,
            'place_issued' => $request->place_issued,
            'reporting_year' => $reportingYear,
        ];

        // ✅ Handle signature upload
        if ($request->hasFile('signature')) {
            $file = $request->file('signature');
            $fileName = 'signature_' . $personalInfo->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/signatures'), $fileName);
            $certificationData['signature_path'] = 'uploads/signatures/' . $fileName;
        }

        SalnCertification::updateOrCreate(
            ['personal_information_id' => $personalInfo->id],
            $certificationData
        );



return redirect()->route('saln.index')->with('success', 'SALN saved successfully!');

    }
}
