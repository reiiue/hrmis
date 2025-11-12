<?php

namespace App\Http\Controllers;

use setasign\Fpdi\Fpdi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PersonalInformation;
use App\Models\GovernmentId;
use App\Models\User;
use Carbon\Carbon;

class PdsPdfController extends Controller
{
    /**
     * Helper to center text between two X coordinates
     */
    private function centerText($pdf, $y, $text, $xStart, $xEnd, $fontSize = 7)
    {
        $pdf->SetFont('Arial', '', $fontSize);
        $width = $pdf->GetStringWidth($text);
        $x = $xStart + (($xEnd - $xStart) / 2) - ($width / 2);
        $pdf->Text($x, $y, $text);
    }

    /**
     * Format a date string or return empty string
     */
    private function formatDate($date)
    {
        if (!$date) {
            return '';
        }

        return Carbon::parse($date)->format('m/d/Y');
    }

    /**
     * Draw a checkbox: place a check at yesX if $value is truthy ("yes"), else at noX
     */
    private function drawCheckbox($pdf, $yesX, $noX, $y, $value)
    {
        $pdf->SetFont('ZapfDingbats', '', 7);
        if (strtolower((string)$value) === 'yes') {
            $pdf->Text($yesX, $y, chr(52));
        } else {
            $pdf->Text($noX, $y, chr(52));
        }
    }

public function download($userId = null)
{
    $user = auth()->user();

    // Employees can only download their own PDS
    if ($user->role === 'Employee') {
        $userId = $user->id;
    } 
    elseif ($user->role === 'Admin' && $userId === null) {
        $userId = $user->id; // Default to Admin's own PDS
    }
    $filePath = public_path('pdfs/pds_clean.pdf'); // Path to PDS PDF
    $pdf = new Fpdi();

    // Fetch the employee by ID
    $user = User::with(['personalInformation' => function($q) {
        $q->with([
            'dualCitizenshipCountry',
            'permanentAddress',
            'residentialAddress',
            'parents',
            'spouse',
            'children',
            'educationalBackgrounds',
            'civilServiceEligibilities',
            'workExperiences',
            'membershipAssociations',
            'learningDevelopments',
            'specialSkillsHobbies',
            'relationshipToAuthority',
            'legalCase',
            'employmentSeparation',
            'politicalActivity',
            'immigrationStatus',
            'specialStatus',
        ]);
    }])->findOrFail($userId);

    $personalInfo = $user->personalInformation;

    if (!$personalInfo) {
        return abort(404, 'PDS not found');
    }

        // -----------------------------
        // Personal Information
        // -----------------------------
        $last_name   = $personalInfo?->last_name ?? '';
        $first_name  = $personalInfo?->first_name ?? '';
        $middle_name = $personalInfo?->middle_name ?? '';
        $suffix      = $personalInfo?->suffix ?? '';
        $date_of_birth = $this->formatDate($personalInfo?->date_of_birth);
        $place_of_birth = $personalInfo?->place_of_birth ?? '';
        $sex         = $personalInfo?->sex ?? '';
        $civil_status = $personalInfo?->civil_status ?? '';
        $height = $personalInfo?->height ?? '';
        $weight = $personalInfo?->weight ?? '';
        $blood_type = $personalInfo?->blood_type ?? '';
        $agency_employee_no = $personalInfo?->agency_employee_no ?? '';
        $citizenship = $personalInfo?->citizenship ?? '';
        $dual_type = $personalInfo?->dual_citizenship_type ?? '';
        $dual_country_name = $personalInfo?->dualCitizenshipCountry?->name ?? '';
        $telephone_number = $personalInfo?->telephone_no ?? '';
        $mobile_number = $personalInfo?->mobile_no ?? '';
        $email = $personalInfo->email ?? '';

        // -----------------------------
        // Government IDs
        // -----------------------------
    $governmentIds = GovernmentId::where('personal_information_id', $personalInfo->id)->first();
    $gsis_id = $governmentIds?->gsis_id ?? '';
    $pagibig_id = $governmentIds?->pagibig_id ?? '';
    $philhealth_id = $governmentIds?->philhealth_id ?? '';
    $sss_id = $governmentIds?->sss_id ?? '';
    $tin_id = $governmentIds?->tin_id ?? '';

        // -----------------------------
        // Permanent Address
        // -----------------------------
        $perm_house = $personalInfo?->permanentAddress?->house_block_lot_no ?? '';
        $perm_street = $personalInfo?->permanentAddress?->street ?? '';
        $perm_subdivision = $personalInfo?->permanentAddress?->subdivision ?? '';
        $perm_barangay = $personalInfo?->permanentAddress?->barangay ?? '';
        $perm_city = $personalInfo?->permanentAddress?->city ?? '';
        $perm_province = $personalInfo?->permanentAddress?->province ?? '';
        $perm_zip = $personalInfo?->permanentAddress?->zip_code ?? '';

        // -----------------------------
        // Residential Address
        // -----------------------------
        $res_house = $personalInfo?->residentialAddress?->house_block_lot_no ?? '';
        $res_street = $personalInfo?->residentialAddress?->street ?? '';
        $res_subdivision = $personalInfo?->residentialAddress?->subdivision ?? '';
        $res_barangay = $personalInfo?->residentialAddress?->barangay ?? '';
        $res_city = $personalInfo?->residentialAddress?->city ?? '';
        $res_province = $personalInfo?->residentialAddress?->province ?? '';
        $res_zip = $personalInfo?->residentialAddress?->zip_code ?? '';

        //Parents
        $father_surname = $personalInfo?->parents?->father_last_name ?? '';
        $father_first_name = $personalInfo?->parents?->father_first_name ?? '';
        $father_middle_name = $personalInfo?->parents?->father_middle_name ?? '';
        $father_extension_name = $personalInfo?->parents?->father_extension_name ?? '';
        $mother_surname = $personalInfo?->parents?->mother_last_name ?? '';
        $mother_first_name = $personalInfo?->parents?->mother_first_name ?? '';
        $mother_middle_name = $personalInfo?->parents?->mother_middle_name ?? '';

        //Spouse
        $spouse_surname = $personalInfo?->spouse?->last_name ?? '';
        $spouse_first_name = $personalInfo?->spouse?->first_name ?? '';
        $spouse_middle_name = $personalInfo?->spouse?->middle_name ?? '';
        $spouse_extension_name = $personalInfo?->spouse?->name_extension ?? ''; 
        $spouse_occupation = $personalInfo?->spouse?->occupation ?? '';
        $employer_business_name = $personalInfo?->spouse?->employer_business_name ?? '';
        $business_address = $personalInfo?->spouse?->business_address ?? '';
        $spouse_tel = $personalInfo?->spouse?->telephone_no ?? '';

        // Relationship to Authority
        $relationAuthority = $personalInfo?->relationshipToAuthority;
        $thirdDegree = $relationAuthority?->within_third_degree ?? '';
        $fourthDegree = $relationAuthority?->within_fourth_degree ?? '';
        $relationDetails = $relationAuthority?->details ?? '';

        //Legal Case
        $legalCase = $personalInfo?->legalCase;

        $has_admin_offense = $legalCase?->has_admin_offense ?? '';
        $offense_details = $legalCase?->offense_details ?? '';

        $has_criminal_case = $legalCase?->has_criminal_case ?? '';
        $date_filed = $legalCase?->date_filed
            ? Carbon::parse($legalCase->date_filed)->format('m/d/Y')
            : '';
        $status_of_case = $legalCase?->status_of_case ?? '';

        $has_been_convicted = $legalCase?->has_been_convicted ?? '';
        $conviction_details = $legalCase?->conviction_details ?? ''; 

        // -----------------------------
        // Children
        // -----------------------------
        $children = $personalInfo?->children ?? collect();

        $employmentSeparation = $personalInfo->employmentSeparation; // relationship

        $has_been_separated = $employmentSeparation->has_been_separated ?? 'no';
        $separation_details = $employmentSeparation->details ?? '';

        // -----------------------------
        // PDF Fill
        // -----------------------------
        $pageCount = $pdf->setSourceFile($filePath);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {


            $pdf->AddPage('P', [215.9, 330.2]);
            $templateId = $pdf->importPage($pageNo);
                // 👇 Apply different position per page

            if ($pageNo === 2) {
                // Page 2 custom placement
                $pdf->useTemplate($templateId, -22, 0, 280 - 20); 
                // 5 mm from left, 15 mm from top, width = 210 mm
            } else {
                // Page 1 (and others) use your preferred SALN-style layout
                $pdf->useTemplate($templateId, -15, 4, 267 - 20); 
                // -15 mm left offset, -2 mm top offset, width = 247 mm
            }


            

            if ($pageNo === 1) {
                $pdf->SetFont('Arial', '', 10);
                $pdf->SetTextColor(0, 0, 0);

                // ---- Personal Info ----
                $pdf->SetFont('Arial', '', 10); // set font
                $pdf->SetXY(44.9, 47.6); // move to position
                $pdf->Cell(162, 6, $last_name, 0, 0, 'L'); // (width, height, text, border, ln, align)

                $pdf->SetXY(44.9, 53.8);
                $pdf->Cell(113.4, 6, $first_name, 0, 0, 'L');

                $pdf->SetXY(47, 62);
                $pdf->Cell(50, 6, $middle_name, 1, 0, 'L');

                $pdf->SetXY(185, 56);
                $pdf->Cell(15, 6, $suffix, 1, 0, 'L');

                $pdf->SetXY(47, 70);
                $pdf->Cell(50, 6, $date_of_birth, 1, 0, 'L');

                $pdf->SetXY(55, 66);
                $pdf->Cell(50, 6, $place_of_birth, 1, 0, 'L');

                $pdf->SetXY(55, 89.5);
                $pdf->Cell(25, 6, $height, 1, 0, 'L');

                $pdf->SetXY(55, 95);
                $pdf->Cell(25, 6, $weight, 1, 0, 'L');

                $pdf->SetXY(55, 100.5);
                $pdf->Cell(25, 6, $blood_type, 1, 0, 'L');

                $pdf->SetXY(55, 136);
                $pdf->Cell(25, 6, $agency_employee_no, 1, 0, 'L');

                $pdf->SetXY(122, 124.5);
                $pdf->Cell(40, 6, $telephone_number, 1, 0, 'L');

                $pdf->SetXY(122, 130.5);
                $pdf->Cell(40, 6, $mobile_number, 1, 0, 'L');

                $pdf->SetXY(122, 136);
                $pdf->Cell(50, 6, $email, 1, 0, 'L');


                // ---- Parents ----
                $pdf->Text(55, 180.5, $father_surname);
                $pdf->Text(55, 185.5, $father_first_name);
                $pdf->Text(55, 190.5, $father_middle_name); 
                $pdf->Text(105, 186, $father_extension_name);
                $pdf->Text(55, 200.5, $mother_surname);
                $pdf->Text(55, 205.5, $mother_first_name);
                $pdf->Text(55, 210.5, $mother_middle_name);

                // ---- Spouse ----
                $pdf->Text(55, 145.5, $spouse_surname);
                $pdf->Text(55, 150.5, $spouse_first_name);
                $pdf->Text(55, 155.5, $spouse_middle_name);
                $pdf->Text(105, 151, $spouse_extension_name);
                $pdf->Text(55, 160.5, $spouse_occupation);
                $pdf->Text(55, 165.5, $employer_business_name);
                $pdf->Text(55, 170.5, $business_address);
                $pdf->Text(55, 175.5, $spouse_tel);

                // ---- Government IDs ----
                $pdf->Text(55, 106.5, $gsis_id); 
                $pdf->Text(55, 112.5, $pagibig_id);
                $pdf->Text(55, 118.5, $philhealth_id);
                $pdf->Text(55, 124.5, $sss_id);
                $pdf->Text(55, 130, $tin_id);

                // ---- Sex ----
                $pdf->SetFont('ZapfDingbats', '', 7); 
                if (strtolower($sex) === 'male') {
                    $pdf->Text(55, 71.5, chr(52)); 
                } elseif (strtolower($sex) === 'female') {
                    $pdf->Text(79.5, 71.5, chr(52)); 
                }

                // ---- Civil Status ----
                if (strtolower($civil_status) === 'single') {
                    $pdf->Text(55, 76.5, chr(52)); 
                } elseif (strtolower($civil_status) === 'married') {
                    $pdf->Text(79.5, 76.5, chr(52));
                } elseif (strtolower($civil_status) === 'widowed') {
                    $pdf->Text(55, 80, chr(52)); 
                } elseif (strtolower($civil_status) === 'separated') {
                    $pdf->Text(79.5, 80, chr(52)); 
                } 

                // ---- Citizenship ----
                if (strtolower($citizenship) === 'filipino') {
                    $pdf->Text(135.3, 58, chr(52)); 
                } elseif (strtolower($citizenship) === 'dual citizenship') {
                    $pdf->Text(151.3, 58, chr(52)); 

                    if (strtolower($dual_type) === 'by birth') {
                        $pdf->Text(155.5, 61.6, chr(52)); 
                    } elseif (strtolower($dual_type) === 'by naturalization') {
                        $pdf->Text(169, 61.6, chr(52)); 
                    }

                    if (!empty($dual_country_name)) {
                        $pdf->SetFont('Arial', '', 8);
                        $pdf->SetXY(150, 68);
                        $pdf->Cell(50, 5, $dual_country_name, 0, 0);
                    }
                }

                // ---- Permanent Address (Centered) ----
                $this->centerText($pdf, 99.5, $perm_house, 132, 140);
                $this->centerText($pdf, 99.5, $perm_street, 159, 188);
                $this->centerText($pdf, 105.5, $perm_subdivision, 132, 140);
                $this->centerText($pdf, 105.5, $perm_barangay, 159, 188);
                $this->centerText($pdf, 111.5, $perm_city, 132, 140);
                $this->centerText($pdf, 111.5, $perm_province, 159, 188);
                $this->centerText($pdf, 118, $perm_zip, 100, 210);

                // ---- Residential Address (Centered) ----
                $this->centerText($pdf, 77, $res_house, 132, 140);
                $this->centerText($pdf, 77, $res_street, 159, 188);
                $this->centerText($pdf, 82.5, $res_subdivision, 132, 140);
                $this->centerText($pdf, 82.5, $res_barangay, 159, 188);
                $this->centerText($pdf, 88.5, $res_city, 132, 140);
                $this->centerText($pdf, 88.5, $res_province, 159, 188);
                $this->centerText($pdf, 95, $res_zip, 100, 210);

                // ---- Children ----
                $pdf->SetFont('Arial', '', 8);
                $yStart = 150.5;   // starting Y for first child row
                $rowHeight = 5;    // row spacing
                $maxRows = 12;     // max children rows in PDS

                for ($i = 0; $i < $maxRows; $i++) {
                    $y = $yStart + ($i * $rowHeight);

                    if (isset($children[$i])) {
                        $child = $children[$i];
                        $pdf->Text(122, $y, $child->full_name);

                                        $dob = $this->formatDate($child->date_of_birth);
                        $pdf->Text(170, $y, $dob);
                    }
                }
            }

            // -----------------------------
            // Educational Background
            // -----------------------------

            $educations = $personalInfo?->educationalBackgrounds ?? collect();
            if ($pageNo === 1) {
                $pdf->SetFont('Arial', '', 8);
                $yStart = 231;    // starting Y for education section
                $rowHeight = 7;  // spacing between rows
                $maxRows = 5;    // PDS has 5 rows (Elem, Sec, Voc, College, Graduate)

                $levels = [
                    'Elementary',
                    'Secondary',
                    'VocationalCourse',
                    'College',
                    'GraduateStudies'
                ];

                foreach ($levels as $idx => $level) {
                    $y = $yStart + ($idx * $rowHeight);
                        $edu = $educations->firstWhere('level', $level);

                        if ($edu) {
                            $pdf->Text(54, $y, $edu->school_name);
                            $pdf->Text(96, $y, $edu->degree_course);
                            $pdf->Text(134, $y, $edu->period_from);
                            $pdf->Text(144.5, $y, $edu->period_to);
                            $pdf->Text(157, $y, $edu->highest_level_unit_earned);
                            $pdf->Text(171, $y, $edu->year_graduated);
                            $pdf->Text(184.5, $y, $edu->scholarship_honors);
                        }
                }
            }


            // Civil Service Eligibilities
            $eligibilities = $personalInfo?->civilServiceEligibilities ?? collect();

            if ($pageNo === 2) {
                $pdf->SetFont('Arial', '', 7);
                $yStart = 25; // starting Y position
                $rowHeight = 7;
                $maxRows = 7;

                for ($i = 0; $i < $maxRows; $i++) {
                    $y = $yStart + ($i * $rowHeight);

                    if (isset($eligibilities[$i])) {
                        $elig = $eligibilities[$i];

                        // Centered text between column boundaries
                        $this->centerText($pdf, $y, $elig->eligibility_type ?? '', 25, 75); // Eligibility type
                        $this->centerText($pdf, $y, $elig->rating ?? '', 78, 95);           // Rating
                        $this->centerText($pdf, $y, $this->formatDate($elig->exam_date), 97, 115); // Date of exam
                        $this->centerText($pdf, $y, $elig->exam_place ?? '', 116, 170);     // Place of exam
                        $this->centerText($pdf, $y, $elig->license_number ?? '', 170, 180); // License number
                        $this->centerText($pdf, $y, $this->formatDate($elig->license_validity), 185, 193); // Validity date
                    }
                }
            }

            // Work Experience
            $workExperiences = $personalInfo?->workExperiences ?? collect();

            if ($pageNo === 2) {
                $pdf->SetFont('Arial', '', 7);
                $yStart = 97; // starting Y position below eligibilities (adjust based on your form layout)
                $rowHeight = 6;
                $maxRows = 20; // maximum rows for work experience table on the page

                for ($i = 0; $i < $maxRows; $i++) {
                    $y = $yStart + ($i * $rowHeight);

                    if (isset($workExperiences[$i])) {
                        $work = $workExperiences[$i];

                        // Format dates
                        $dateFrom = $this->formatDate($work->inclusive_date_from_work);
                        $dateTo = $this->formatDate($work->inclusive_date_to_work);

                        // Draw text centered within columns
                        $this->centerText($pdf, $y, $dateFrom, 24, 40);                     // From
                        $this->centerText($pdf, $y, $dateTo, 41, 50);                       // To
                        $this->centerText($pdf, $y, $work->position_title ?? '', 60, 90);   // Position title
                        $this->centerText($pdf, $y, $work->department_agency ?? '', 96, 140); // Department/Agency
                        $this->centerText($pdf, $y, number_format($work->monthly_salary ?? 0, 2), 141, 155); // Monthly salary
                        $this->centerText($pdf, $y, $work->salary_grade_step ?? '', 156, 165); // Salary grade/step
                        $this->centerText($pdf, $y, $work->status_appointment ?? '', 166, 185); // Status of appointment
                        $this->centerText($pdf, $y, $work->gov_service ?? '', 183, 195);      // Gov’t service (Y/N)
                    }
                }
            }

            // Membership in Association/Organization
            $memberships = $personalInfo?->membershipAssociations ?? collect();

            if ($pageNo === 3) { // Assuming Membership appears on page 3 (adjust if needed)
                $pdf->SetFont('Arial', '', 7);
                $yStart = 30; // starting Y position for Membership section (adjust to fit your PDF)
                $rowHeight = 6;
                $maxRows = 10; // adjust based on available space in your layout

                for ($i = 0; $i < $maxRows; $i++) {
                    $y = $yStart + ($i * $rowHeight);

                    if (isset($memberships[$i])) {
                        $member = $memberships[$i];

                        // Format date fields
                        $periodFrom = $this->formatDate($member->period_from);
                        $periodTo = $this->formatDate($member->period_to);

                        // Draw text centered within your defined column coordinates
                        $this->centerText($pdf, $y, $member->organization_name ?? '', 28, 90); // Name of Organization
                        $this->centerText($pdf, $y, $periodFrom, 100, 110);                    // From
                        $this->centerText($pdf, $y, $periodTo, 107, 130);                     // To
                        $this->centerText($pdf, $y, $member->number_of_hours ?? '', 118, 145); // Number of hours
                        $this->centerText($pdf, $y, $member->position ?? '', 145, 190);       // Position
                    }
                }
            }

            // Learning and Development (L&D)
            $trainings = $personalInfo?->learningDevelopments ?? collect();

            if ($pageNo === 3) { // Assuming Learning and Development appears on Page 3
                $pdf->SetFont('Arial', '', 7);
                $yStart = 94; // starting Y position (adjust based on your PDF layout)
                $rowHeight = 6;
                $maxRows = 10; // adjust according to available table rows on the PDF

                for ($i = 0; $i < $maxRows; $i++) {
                    $y = $yStart + ($i * $rowHeight);

                    if (isset($trainings[$i])) {
                        $training = $trainings[$i];

                        // Format dates
                        $from = $this->formatDate($training->inclusive_date_from);
                        $to = $this->formatDate($training->inclusive_date_to);

                        // Fill in text using centerText helper — adjust X coordinates to match your PDF columns
                        $this->centerText($pdf, $y, $training->training_title ?? '', 27, 90);    // Title of Learning and Development
                        $this->centerText($pdf, $y, $from, 100, 110);                             // Inclusive Dates (From)
                        $this->centerText($pdf, $y, $to, 107, 130);                              // Inclusive Dates (To)
                        $this->centerText($pdf, $y, $training->number_of_hours ?? '', 119, 145); // Number of Hours
                        $this->centerText($pdf, $y, $training->type_of_ld ?? '', 122, 170);      // Type (e.g. Managerial, Supervisory, Technical)
                        $this->centerText($pdf, $y, $training->conducted_by ?? '', 150, 200);    // Conducted/Sponsored By
                    }
                }
            }

            // Special Skills / Non-Academic Distinctions / Memberships
            $specials = $personalInfo?->specialSkillsHobbies ?? collect();

            if ($pageNo === 3) { 
                $pdf->SetFont('Arial', '', 7);
                $yStart = 231; // adjust to match row start
                $rowHeight = 6;
                $maxRows = 7; // number of table rows in your PDF

                for ($i = 0; $i < $maxRows; $i++) {
                    $y = $yStart + ($i * $rowHeight);

                    if (isset($specials[$i])) {
                        $record = $specials[$i];

                        // Same layout style as your L&D table
                        $this->centerText($pdf, $y, $record->special_skills ?? '', 10, 80);              // Special Skills
                        $this->centerText($pdf, $y, $record->non_academic_distinctions ?? '', 72, 150);  // Non-Academic Distinctions
                        $this->centerText($pdf, $y, $record->membership_in_organization ?? '', 151, 200); // Membership in Organization
                    }
                }
            }


            if ($pageNo === 4) {
                $pdf->SetFont('Arial', '', 8);

                // Checkboxes for Within 3rd Degree and Within 4th Degree
                $this->drawCheckbox($pdf, 135.3, 154, 24, $thirdDegree);
                $this->drawCheckbox($pdf, 135.3, 154, 28.7, $fourthDegree);

                // Details textarea
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetXY(140, 34);
                $pdf->MultiCell(170, 4, $relationDetails);
            }


            if ($pageNo === 4) {

                // ===== Q1: ADMINISTRATIVE OFFENSE =====
                $this->drawCheckbox($pdf, 135, 154, 42.5, $has_admin_offense);

                // Details
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetXY(140, 48);
                $pdf->MultiCell(170, 4, $offense_details);

                // ===== Q2: CRIMINAL CASE =====
                $this->drawCheckbox($pdf, 135, 155, 57.2, $has_criminal_case);

                $criminalDetails = trim(($date_filed ? $date_filed . "\n" : '') . ($status_of_case ?? ''));

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetXY(157, 62);
                $pdf->MultiCell(170, 4, $criminalDetails);

                // ===== Q3: CONVICTION =====
                $this->drawCheckbox($pdf, 134.7, 156, 75.5, $has_been_convicted);

                // Details
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetXY(140, 81);
                $pdf->MultiCell(170, 4, $conviction_details);

                // ===== Q4: SEPARATION FROM SERVICE =====
                $this->drawCheckbox($pdf, 134.5, 156, 90, $has_been_separated);

                // Details
                $pdf->SetFont('Arial', '', 7);
                $pdf->SetXY(140, 93);
                $pdf->MultiCell(170, 4, $separation_details ?? '');


                // ===== Q5: POLITICAL ACTIVITIES =====

                // Fetch political activities (assuming relationship exists)
                $political = $personalInfo->politicalActivity ?? null;

                // Question 1: Have you been a candidate in a national or local election?
                $this->drawCheckbox($pdf, 135, 158, 102, $political?->has_been_candidate ?? 'no');

                // Election Details
                $pdf->SetFont('Arial', '', 7);
                $pdf->SetXY(157, 103);
                $pdf->MultiCell(170, 4, $political->election_details ?? '');

                // Question 2: Have you resigned from the government service during the campaign period?
                $this->drawCheckbox($pdf, 135, 158, 111, $political?->has_resigned_for_campaigning ?? 'no');

                // Campaign Details
                $pdf->SetFont('Arial', '', 7);
                $pdf->SetXY(157, 113);
                $pdf->MultiCell(170, 4, $political->campaign_details ?? '');


                // ===== Q6: IMMIGRATION STATUS =====

                // Fetch immigration status (assuming relationship exists)
                $immigration = $personalInfo->immigrationStatus ?? null;

                // Question: Are you a dual citizen by birth or naturalization?
                $this->drawCheckbox($pdf, 135, 157.5, 121.5, $immigration?->has_immigrant_status ?? 'no');

                // Country
                $pdf->SetFont('Arial', '', 7);
                $pdf->SetXY(138, 126);
                $countryName = $immigration && $immigration->country ? $immigration->country->name : '';
                $pdf->MultiCell(170, 4, $countryName);

                // ===== Q7: SPECIAL STATUS =====

                // Fetch special status (assuming relationship exists)
                $specialStatus = $personalInfo->specialStatus ?? null;

                // ========== Indigenous Group Membership ==========
                $this->drawCheckbox($pdf, 135, 158, 143, $specialStatus?->is_indigenous_member ?? 'no');

                // Indigenous Group Name
                $pdf->SetFont('Arial', '', 7);
                $pdf->SetXY(170, 143.5);
                $indigenousGroup = $specialStatus && $specialStatus->indigenous_group_name ? $specialStatus->indigenous_group_name : '';
                $pdf->MultiCell(170, 4, $indigenousGroup);

                // ========== Person With Disability ==========
                $this->drawCheckbox($pdf, 135, 158, 150.3, $specialStatus?->is_person_with_disability ?? 'no');

                // PWD ID Number
                $pdf->SetFont('Arial', '', 7);
                $pdf->SetXY(170, 151.5);
                $pwdId = $specialStatus && $specialStatus->pwd_id_number ? $specialStatus->pwd_id_number : '';
                $pdf->MultiCell(170, 4, $pwdId);

                // ========== Solo Parent ==========
                $this->drawCheckbox($pdf, 135, 158, 158.3, $specialStatus?->is_solo_parent ?? 'no');

                // Solo Parent ID Number
                $pdf->SetFont('Arial', '', 7);
                $pdf->SetXY(170, 158.5);
                $soloParentId = $specialStatus && $specialStatus->solo_parent_id_number ? $specialStatus->solo_parent_id_number : '';
                $pdf->MultiCell(170, 4, $soloParentId);


            }
        }

        
        

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="pds_'.$user->id.'.pdf"');
    } 
}
