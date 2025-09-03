<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalInformation;
use App\Models\Spouse;
use Illuminate\Support\Facades\Auth;
use App\Models\Country;
use App\Models\Address;
use App\Models\GovernmentId;
use App\Models\Child;
use App\Models\ParentInfo; // <— import Parents model
use App\Models\EducationalBackground;
use App\Models\CivilServiceEligibility; // <-- add this
use App\Models\WorkExperience; // <-- NEW
use App\Models\MembershipAssociation; 


class PDSController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $personalInfo = PersonalInformation::where('user_id', $user->id)->first();
        $countries = Country::orderBy('name')->get();

        $residentialAddress = null;
        $permanentAddress = null;
        $governmentIds = null;
        $spouse = null;
        $children = collect(); // default empty
        $parents = null; // <— add parents
        $educationalBackgrounds = collect(); // default empty
        $eligibilities = collect(); // <-- default empty
        $work_experiences = collect(); // <-- NEW
        $membership_associations = collect(); // <-- NEW

        if ($personalInfo) {
            $residentialAddress = $personalInfo->addresses()
                ->where('address_type', 'Residential')
                ->first();

            $permanentAddress = $personalInfo->addresses()
                ->where('address_type', 'Permanent')
                ->first();

            $governmentIds = $personalInfo->governmentIds()->first();
            $spouse = $personalInfo->spouse()->first();
            $children = $personalInfo->children()->get();
            $parents = $personalInfo->parents()->first(); // <— load parents
            $educationalBackgrounds = $personalInfo->educationalBackgrounds()->get();
            $eligibilities = $personalInfo->civilServiceEligibilities()->get(); // <-- load eligibilities
            $work_experiences = $personalInfo->workExperiences()->get(); // <-- load
            $membership_associations = $personalInfo->membershipAssociations()->get();

        }

        return view('pds.index', compact(
            'personalInfo',
            'countries',
            'residentialAddress',
            'permanentAddress',
            'governmentIds',
            'spouse',
            'children',
            'parents', // <— pass to blade
            'educationalBackgrounds',
            'eligibilities', // <-- pass to blade
            'work_experiences', // <-- pass to blade
            'membership_associations' // <-- pass to blade
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

            // Government IDs
            'gsis_id' => 'nullable|string|max:50',
            'pagibig_id' => 'nullable|string|max:50',
            'philhealth_id' => 'nullable|string|max:50',
            'sss_id' => 'nullable|string|max:50',
            'tin_no' => 'nullable|string|max:20',

            // Spouse
            'spouse_last_name' => 'nullable|string|max:255',
            'spouse_first_name' => 'nullable|string|max:255',
            'spouse_middle_name' => 'nullable|string|max:255',
            'spouse_name_extension' => 'nullable|string|max:10',
            'spouse_occupation' => 'nullable|string|max:255',
            'employer_business_name' => 'nullable|string|max:255',
            'business_address' => 'nullable|string|max:255',
            'spouse_telephone_no' => 'nullable|string|max:255',

            // Parents
            'father_last_name' => 'nullable|string|max:255',
            'father_first_name' => 'nullable|string|max:255',
            'father_middle_name' => 'nullable|string|max:255',
            'father_extension_name' => 'nullable|string|max:10',
            'mother_last_name' => 'nullable|string|max:255',
            'mother_first_name' => 'nullable|string|max:255',
            'mother_middle_name' => 'nullable|string|max:255',
            
            //Civl Service Eligibilities
            'eligibility_type.*' => 'nullable|string|max:255',
            'rating.*' => 'nullable|string|max:50',
            'exam_date.*' => 'nullable|date',
            'exam_place.*' => 'nullable|string|max:255',
            'license_number.*' => 'nullable|string|max:100',
            'license_validity.*' => 'nullable|date',

            // Work Experience
            'inclusive_date_from.*' => 'nullable|date',
            'inclusive_date_to.*' => 'nullable|date',
            'position_title.*' => 'nullable|string|max:255',
            'department_agency.*' => 'nullable|string|max:255',
            'monthly_salary.*' => 'nullable|numeric|min:0',
            'salary_grade_step.*' => 'nullable|string|max:50',
            'status_appointment.*' => 'nullable|string|max:255',
            'gov_service.*' => 'nullable|in:Y,N',

            // Membership Associations
            'organization_name.*' => 'nullable|string|max:255',
            'period_from.*' => 'nullable|date',
            'period_to.*' => 'nullable|date',
            'number_of_hours.*' => 'nullable|integer|min:0',
            'position.*' => 'nullable|string|max:255',
        ]);

        // --- Save Personal Information ---
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
            'telephone_no', 
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
                ['personal_information_id' => $personalInformation->id, 'address_type' => 'Residential'],
                $residential
            );
        }

        // --- Permanent Address ---
        if ($permanent = $request->input('address.permanent')) {
            Address::updateOrCreate(
                ['personal_information_id' => $personalInformation->id, 'address_type' => 'Permanent'],
                $permanent
            );
        }

        // --- Government IDs ---
        GovernmentId::updateOrCreate(
            ['personal_information_id' => $personalInformation->id],
            [
                'gsis_id' => $request->input('gsis_id') ?? null,
                'pagibig_id' => $request->input('pagibig_id') ?? null,
                'philhealth_id' => $request->input('philhealth_id') ?? null,
                'sss_id' => $request->input('sss_id') ?? null,
                'tin_no' => $request->input('tin_no') ?? null,
            ]
        );

        // --- Spouse Information ---
        Spouse::updateOrCreate(
            ['personal_information_id' => $personalInformation->id],
            [
                'last_name' => $request->input('spouse_last_name') ?? null,
                'first_name' => $request->input('spouse_first_name') ?? null,
                'middle_name' => $request->input('spouse_middle_name') ?? null,
                'name_extension' => $request->input('spouse_name_extension') ?? null,
                'occupation' => $request->input('spouse_occupation') ?? null,
                'employer_business_name' => $request->input('employer_business_name') ?? null,
                'business_address' => $request->input('business_address') ?? null,
                'telephone_no' => $request->input('spouse_telephone_no') ?? null,
            ]
        );

        // --- Parents Information ---
        ParentInfo::updateOrCreate(
            ['personal_information_id' => $personalInformation->id],
            [
                'father_last_name' => $request->input('father_last_name') ?? null,
                'father_first_name' => $request->input('father_first_name') ?? null,
                'father_middle_name' => $request->input('father_middle_name') ?? null,
                'father_extension_name' => $request->input('father_extension_name') ?? null,
                'mother_last_name' => $request->input('mother_last_name') ?? null,
                'mother_first_name' => $request->input('mother_first_name') ?? null,
                'mother_middle_name' => $request->input('mother_middle_name') ?? null,
            ]
        );


        // --- Educational Background ---
        $levels = [
            'Elementary'      => 'elementary',
            'Secondary'       => 'secondary',
            'VocationalCourse'=> 'vocational',
            'College'         => 'college',
            'GraduateStudies' => 'graduate',
        ];

        foreach ($levels as $level => $fieldPrefix) {
            EducationalBackground::updateOrCreate(
                [
                    'personal_information_id' => $personalInformation->id,
                    'level' => $level, // ENUM value stored in DB
                ],
                [
                    'school_name'               => $request->input("{$fieldPrefix}_school") ?? null,
                    'degree_course'             => $request->input("{$fieldPrefix}_degree") ?? null,
                    'period_from'               => $request->input("{$fieldPrefix}_period_from") ?? null,
                    'period_to'                 => $request->input("{$fieldPrefix}_period_to") ?? null,
                    'highest_level_unit_earned' => $request->input("{$fieldPrefix}_highest_level") ?? null,
                    'year_graduated'            => $request->input("{$fieldPrefix}_year_graduated") ?? null,
                    'scholarship_honors'        => $request->input("{$fieldPrefix}_honors") ?? null,
                ]
            );
}





        // --- Children ---
        Child::where('personal_information_id', $personalInformation->id)->delete();
        if ($request->has('children')) {
            foreach ($request->children as $childData) {
                if (!empty($childData['full_name']) || !empty($childData['date_of_birth'])) {
                    Child::create([
                        'personal_information_id' => $personalInformation->id,
                        'full_name' => $childData['full_name'] ?? null,
                        'date_of_birth' => $childData['date_of_birth'] ?? null,
                    ]);
                }
            }
        }

        // --- Civil Service Eligibilities ---
        CivilServiceEligibility::where('personal_information_id', $personalInformation->id)->delete();

        $eligibilityTypes = $request->input('eligibility_type', []);
        foreach ($eligibilityTypes as $index => $type) {
            if ($type || $request->rating[$index] || $request->exam_date[$index]) {
                CivilServiceEligibility::create([
                    'personal_information_id' => $personalInformation->id,
                    'eligibility_type' => $type ?? null,
                    'rating' => $request->rating[$index] ?? null,
                    'exam_date' => $request->exam_date[$index] ?? null,
                    'exam_place' => $request->exam_place[$index] ?? null,
                    'license_number' => $request->license_number[$index] ?? null,
                    'license_validity' => $request->license_validity[$index] ?? null,
                ]);
            }
        }

        // --- Work Experiences ---
        WorkExperience::where('personal_information_id', $personalInformation->id)->delete();

        $fromDates = $request->input('inclusive_date_from', []);
        foreach ($fromDates as $index => $from) {
            if ($from || $request->inclusive_date_to[$index] || $request->position_title[$index]) {
                WorkExperience::create([
                    'personal_information_id' => $personalInformation->id,
                    'inclusive_date_from' => $from ?? null,
                    'inclusive_date_to' => $request->inclusive_date_to[$index] ?? null,
                    'position_title' => $request->position_title[$index] ?? null,
                    'department_agency' => $request->department_agency[$index] ?? null,
                    'monthly_salary' => $request->monthly_salary[$index] ?? null,
                    'salary_grade_step' => $request->salary_grade_step[$index] ?? null,
                    'status_appointment' => $request->status_appointment[$index] ?? null,
                    'gov_service' => $request->gov_service[$index] ?? null,
                ]);
            }
        }

        // --- Membership Associations ---
        MembershipAssociation::where('personal_information_id', $personalInformation->id)->delete();

        $orgNames = $request->input('organization_name', []);
        foreach ($orgNames as $index => $org) {
            if ($org || $request->period_from[$index] || $request->period_to[$index] || $request->position[$index]) {
                MembershipAssociation::create([
                    'personal_information_id' => $personalInformation->id,
                    'organization_name' => $org ?? null,
                    'period_from' => $request->period_from[$index] ?? null,
                    'period_to' => $request->period_to[$index] ?? null,
                    'number_of_hours' => $request->number_of_hours[$index] ?? null,
                    'position' => $request->position[$index] ?? null,
                ]);
            }
        }

        
        

        return redirect()->route('pds.index')->with('success', 'Personal Information updated successfully.');
    }
}
