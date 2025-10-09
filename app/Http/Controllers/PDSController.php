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
use App\Models\LearningDevelopment; // <-- NEW
use App\Models\RelationshipToAuthority;
use App\Models\LegalCase;
use App\Models\EmploymentSeparation; // <-- add this import at the top
use App\Models\PoliticalActivity; // <-- add this import at the top
use App\Models\ImmigrationStatus;
use App\Models\SpecialStatus;
use App\Models\SpecialSkillsHobby;




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
        $learning_developments = collect(); // <-- NEW
        $relationshipToAuthority = null;
        $employmentSeparation = null;
        $legalCase = null;
        $politicalActivity = null;
        $specialStatus = null;

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
            $learning_developments = $personalInfo->learningDevelopments()->get(); // <-- load
            $relationshipToAuthority = $personalInfo->relationshipToAuthority()->first();
            $legalCase = $personalInfo->legalCase()->first();
            $employmentSeparation = $personalInfo->employmentSeparation()->first(); // <-- load this
            $politicalActivity = $personalInfo->politicalActivity()->first();
            $specialStatus = $personalInfo->specialStatus()->first(); // load special status
            $special_skills_hobbies = $personalInfo?->specialSkillsHobbies ?? collect();




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
            'membership_associations', // <-- pass to blade
            'learning_developments', // <-- pass to blade
            'relationshipToAuthority',
            'legalCase',
            'employmentSeparation', // <-- pass to blade
            'politicalActivity', // <-- pass to blade
            'specialStatus',
            'special_skills_hobbies'
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
            'tin_' => 'nullable|string|max:20',

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
            'inclusive_date_from_work.*' => 'nullable|date',
            'inclusive_date_to_work.*' => 'nullable|date',
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

            // Learning & Development

            'training_title.*' => 'nullable|string|max:255',
            'inclusive_date_from.*' => 'nullable|date',
            'inclusive_date_to.*' => 'nullable|date',
            'number_of_hours.*' => 'nullable|integer|min:0',
            'type_of_ld.*' => 'nullable|string|max:255',
            'conducted_by.*' => 'nullable|string|max:255',

            // Relationship To Authority
            'within_third_degree' => 'nullable|in:yes,no',
            'within_fourth_degree' => 'nullable|in:yes,no',
            'details' => 'nullable|string',

            // Legal Cases
            'has_admin_offense'   => 'nullable|in:yes,no',
            'offense_details'     => 'nullable|string',
            'has_criminal_case'    => 'nullable|in:yes,no',
            'date_filed'          => 'nullable|date',
            'status_of_case'      => 'nullable|string|max:255',
            'has_been_convicted'  => 'nullable|in:yes,no',
            'conviction_details'  => 'nullable|string',

            // Employment Separation
            'has_been_separated' => 'nullable|in:yes,no',
            'details' => 'nullable|string',

            //Political Activities
            'has_been_candidate' => 'nullable|in:yes,no',
            'election_details' => 'nullable|string',
            'has_resigned_for_campaigning' => 'nullable|in:yes,no',
            'campaign_details' => 'nullable|string',

            // Immigration Status
            'has_immigrant_status' => 'nullable|in:yes,no',
            'country_id' => 'nullable|exists:countries,id',
            
            // Special Status
            'is_indigenous_member'  => 'nullable|in:yes,no',
            'indigenous_group_name' => 'nullable|string',
            'is_person_with_disability' => 'nullable|in:yes,no',
            'pwd_id_number' => 'nullable|integer',
            'is_solo_parent' => 'nullable|in:yes,no',
            'solo_parent_id_number' => 'nullable|integer',

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
                'tin_id' => $request->input('tin_id') ?? null,
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





        $children = $request->input('children', []); // gets array of children
        $processedChildIds = [];

        foreach ($children as $childData) {
            // Skip empty rows
            if (empty($childData['full_name']) && empty($childData['date_of_birth'])) {
                continue;
            }

            $child = Child::updateOrCreate(
                [
                    'id' => $childData['id'] ?? null, // use existing ID if exists
                    'personal_information_id' => $personalInformation->id,
                ],
                [
                    'full_name' => $childData['full_name'] ?? null,
                    'date_of_birth' => $childData['date_of_birth'] ?? null,
                ]
            );

            $processedChildIds[] = $child->id;
        }

        // Delete removed children
        Child::where('personal_information_id', $personalInformation->id)
            ->whereNotIn('id', $processedChildIds)
            ->delete();



        // --- Civil Service Eligibilities ---
        $eligibilityIdsFromForm = $request->input('eligibility_id', []); // ✅ matches Blade
        $eligibilityTypes       = $request->input('eligibility_type', []);

        $processedIds = [];

        foreach ($eligibilityTypes as $index => $type) {
            $eligibilityId   = $eligibilityIdsFromForm[$index] ?? null;
            $rating          = $request->rating[$index] ?? null;
            $examDate        = $request->exam_date[$index] ?? null;
            $examPlace       = $request->exam_place[$index] ?? null;
            $licenseNumber   = $request->license_number[$index] ?? null;
            $licenseValidity = $request->license_validity[$index] ?? null;

            // ✅ Only process if eligibility_type has a value
            if ($type) {
                $eligibility = CivilServiceEligibility::updateOrCreate(
                    ['id' => $eligibilityId, 'personal_information_id' => $personalInformation->id],
                    [
                        'eligibility_type'  => $type,
                        'rating'            => $rating,
                        'exam_date'         => $examDate,
                        'exam_place'        => $examPlace,
                        'license_number'    => $licenseNumber,
                        'license_validity'  => $licenseValidity,
                    ]
                );

                $processedIds[] = $eligibility->id;
            }
        }

        // ✅ Delete removed rows
        CivilServiceEligibility::where('personal_information_id', $personalInformation->id)
            ->whereNotIn('id', $processedIds)
            ->delete();





        // --- Membership Associations ---
        $assocIdsFromForm = $request->input('membership_association_id', []); // hidden input
        $orgNames         = $request->input('organization_name', []);

        $processedIds = [];

        foreach ($orgNames as $index => $org) {
            $assocId       = $assocIdsFromForm[$index] ?? null;
            $periodFrom    = $request->period_from[$index] ?? null;
            $periodTo      = $request->period_to[$index] ?? null;
            $numHours      = $request->number_of_hours[$index] ?? null;
            $position      = $request->position[$index] ?? null;

            // ✅ Only process if organization_name has a value
            if ($org) {
                $assoc = MembershipAssociation::updateOrCreate(
                    ['id' => $assocId, 'personal_information_id' => $personalInformation->id],
                    [
                        'organization_name' => $org,
                        'period_from'       => $periodFrom,
                        'period_to'         => $periodTo,
                        'number_of_hours'   => $numHours,
                        'position'          => $position,
                    ]
                );

                $processedIds[] = $assoc->id;
            }
        }

        // ✅ Delete removed rows
        MembershipAssociation::where('personal_information_id', $personalInformation->id)
            ->whereNotIn('id', $processedIds)
            ->delete();



        // --- Work Experiences ---
        $workIdsFromForm = $request->input('work_experience_id', []); // hidden input from form
        $workFromDates   = $request->input('inclusive_date_from_work', []);

        $processedIds = [];

        foreach ($workFromDates as $index => $from) {
            $workId            = $workIdsFromForm[$index] ?? null;
            $to                = $request->inclusive_date_to_work[$index] ?? null;
            $positionTitle     = $request->position_title[$index] ?? null;
            $departmentAgency  = $request->department_agency[$index] ?? null;
            $monthlySalary     = $request->monthly_salary[$index] ?? null;
            $salaryGradeStep   = $request->salary_grade_step[$index] ?? null;
            $statusAppointment = $request->status_appointment[$index] ?? null;
            $govService        = $request->gov_service[$index] ?? null;

            // ✅ Only save/update if position_title has a value
            if ($positionTitle) {
                $workExperience = WorkExperience::updateOrCreate(
                    ['id' => $workId, 'personal_information_id' => $personalInformation->id],
                    [
                        'inclusive_date_from_work' => $from,
                        'inclusive_date_to_work'   => $to,
                        'position_title'      => $positionTitle,
                        'department_agency'   => $departmentAgency,
                        'monthly_salary'      => $monthlySalary,
                        'salary_grade_step'   => $salaryGradeStep,
                        'status_appointment'  => $statusAppointment,
                        'gov_service'         => $govService,
                    ]
                );

                $processedIds[] = $workExperience->id;
            }
        }


        // ✅ Delete rows removed from the form
        WorkExperience::where('personal_information_id', $personalInformation->id)
            ->whereNotIn('id', $processedIds)
            ->delete();

        // --- Learning & Development ---
        $ldIdsFromForm   = $request->input('learning_development_id', []); // hidden input
        $trainingTitles  = $request->input('training_title', []);

        $processedIds = [];

        foreach ($trainingTitles as $index => $title) {
            $ldId           = $ldIdsFromForm[$index] ?? null;
            $from           = $request->inclusive_date_from[$index] ?? null;
            $to             = $request->inclusive_date_to[$index] ?? null;
            $numHours       = $request->number_of_hours[$index] ?? null;
            $typeOfLd       = $request->type_of_ld[$index] ?? null;   // ⚠️ check if this should be type_of_ld
            $conductedBy    = $request->conducted_by[$index] ?? null;

            // ✅ Only process if training_title has a value
            if ($title) {
                $ld = LearningDevelopment::updateOrCreate(
                    ['id' => $ldId, 'personal_information_id' => $personalInformation->id],
                    [
                        'training_title'       => $title,
                        'inclusive_date_from'  => $from,
                        'inclusive_date_to'    => $to,
                        'number_of_hours'      => $numHours,
                        'type_of_ld'           => $typeOfLd,  // ⚠️ change column name if needed
                        'conducted_by'         => $conductedBy,
                    ]
                );

                $processedIds[] = $ld->id;
            }
        }

        // ✅ Delete removed rows
        LearningDevelopment::where('personal_information_id', $personalInformation->id)
            ->whereNotIn('id', $processedIds)
            ->delete();

        // --- Special Skills / Hobbies / Non-Academic Distinctions / Membership in Organization ---
        $skillIdsFromForm   = $request->input('special_skills_hobby_id', []); // hidden inputs
        $specialSkills      = $request->input('special_skills', []);
        $nonAcademic        = $request->input('non_academic_distinctions', []);
        $membershipOrg      = $request->input('membership_in_organization', []);

        $processedIds = [];

        foreach ($specialSkills as $index => $skill) {
            $skillId            = $skillIdsFromForm[$index] ?? null;
            $specialSkill       = $specialSkills[$index] ?? null;
            $nonAcademicDist    = $nonAcademic[$index] ?? null;
            $membership         = $membershipOrg[$index] ?? null;

            // ✅ Only process rows that have at least one value
            if ($specialSkill || $nonAcademicDist || $membership) {
                $record = SpecialSkillsHobby::updateOrCreate(
                    ['id' => $skillId, 'personal_information_id' => $personalInformation->id],
                    [
                        'special_skills'            => $specialSkill,
                        'non_academic_distinctions' => $nonAcademicDist,
                        'membership_in_organization'=> $membership,
                    ]
                );

                $processedIds[] = $record->id;
            }
        }

        // ✅ Delete removed rows
        SpecialSkillsHobby::where('personal_information_id', $personalInformation->id)
            ->whereNotIn('id', $processedIds)
            ->delete();



        // --- Relationship To Authority ---
        RelationshipToAuthority::updateOrCreate(
            ['personal_information_id' => $personalInformation->id],
            [
                'within_third_degree'  => $request->input('within_third_degree') ?? null,
                'within_fourth_degree' => $request->input('within_fourth_degree') ?? null,
                'details'              => $request->input('details') ?? null,
            ]
        );

        // --- Legal Cases ---
        LegalCase::updateOrCreate(
            ['personal_information_id' => $personalInformation->id],
            [
                'has_admin_offense'   => $request->input('has_admin_offense') ?? 'no',
                'offense_details'     => $request->input('offense_details') ?? null,
                'has_criminal_case'   => $request->input('has_criminal_case') ?? 'no',
                'date_filed'          => $request->input('date_filed') ?? null,
                'status_of_case'      => $request->input('status_of_case') ?? null,
                'has_been_convicted'  => $request->input('has_been_convicted') ?? 'no',
                'conviction_details'  => $request->input('conviction_details') ?? null,
            ]
        );

        // --- Employment Separation ---
        EmploymentSeparation::updateOrCreate(
            ['personal_information_id' => $personalInformation->id],
            [
                'has_been_separated' => $request->input('has_been_separated') ?? 'no',
                'details'            => $request->input('details') ?? null,
            ]
        );

        PoliticalActivity::updateOrCreate(
            ['personal_information_id' => $personalInformation->id],
            [
                'has_been_candidate' => $request->input('has_been_candidate') ?? 'no',
                'election_details' => $request->input('election_details') ?? null,
                'has_resigned_for_campaigning' => $request->input('has_resigned_for_campaigning') ?? 'no',
                'campaign_details' => $request->input('campaign_details') ?? null,
            ]
        );

        // --- Immigration Status ---
        $hasImmigrantStatus = $request->input('has_immigrant_status');
        $countryId = $request->input('country_id');

        if ($hasImmigrantStatus) {
            ImmigrationStatus::updateOrCreate(
                ['personal_information_id' => $personalInformation->id],
                [
                    'has_immigrant_status' => $hasImmigrantStatus,
                    'country_id' => $hasImmigrantStatus === 'yes' ? $countryId : null,
                ]
            );
        } else {
            // Optional: delete if not answered
            ImmigrationStatus::where('personal_information_id', $personalInformation->id)->delete();
        }

        // --- Special Status ---
        SpecialStatus::updateOrCreate(
            ['personal_information_id' => $personalInformation->id],
            [
                'is_indigenous_member'      => $request->input('is_indigenous_member') ?? 'no',
                'indigenous_group_name'     => $request->input('is_indigenous_member') === 'yes' ? $request->input('indigenous_group_name') : null,
                'is_person_with_disability' => $request->input('is_person_with_disability') ?? 'no',
                'pwd_id_number'             => $request->input('is_person_with_disability') === 'yes' ? $request->input('pwd_id_number') : null,
                'is_solo_parent'            => $request->input('is_solo_parent') ?? 'no',
                'solo_parent_id_number'     => $request->input('is_solo_parent') === 'yes' ? $request->input('solo_parent_id_number') : null,
            ]
        );



    

        return redirect()->route('pds.index')->with('success', 'Personal Information updated successfully.');
    }
}
