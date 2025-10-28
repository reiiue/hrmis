<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use setasign\Fpdi\Fpdi;
use App\Models\PersonalInformation;
use App\Models\AssetsRealProperty;
use App\Models\AssetsPersonalProperty;
use App\Models\Liability;
use App\Models\TotalCosts;
use App\Models\BusinessInterest;
use App\Models\RelativeInGovService;
use App\Models\Agency;

class SalnPdfController extends Controller
{
    /**
     * Helper to center text between two X coordinates.
     */
    private function centerText($pdf, $y, $text, $xStart, $xEnd, $fontSize = 9, $font = 'Times', $style = '')
    {
        $pdf->SetFont($font, $style, $fontSize);
        $width = $pdf->GetStringWidth($text);
        $x = $xStart + (($xEnd - $xStart) / 2) - ($width / 2);
        $pdf->Text($x, $y, $text);
    }

    /**
     * Download SALN as a PDF file.
     */
    public function download()
    {
        $user = Auth::user();
        $personalInfo = PersonalInformation::where('user_id', $user->id)->firstOrFail();
        $reportingYear = now()->year;

        // ✅ Fetch related data
        $real_properties = $personalInfo->assetsRealProperties()->where('reporting_year', $reportingYear)->get();
        $personal_properties = $personalInfo->assetsPersonalProperties()->where('reporting_year', $reportingYear)->get();
        $liabilities = $personalInfo->liabilities()->where('reporting_year', $reportingYear)->get();
        $total_costs = $personalInfo->totalCosts()->where('reporting_year', $reportingYear)->latest()->first();
        $business_interests = $personalInfo->businessInterests()->whereYear('date_of_acquisition', '<=', $reportingYear)->get();
        $relatives_in_gov_service = $personalInfo->relativesInGovService()->get();

        // ✅ Fetch both agencies (personal & spouse)
        $agencies = Agency::where('personal_information_id', $personalInfo->id)->get();
        $personal_agency = $agencies->where('type', 'personal')->first();
        $spouse_agency = $agencies->where('type', 'spouse')->first();
        

        // ✅ Load PDF Template (Legal size)
        $pdf = new Fpdi('P', 'mm', [215.9, 330.2]);
        $templatePath = public_path('pdfs/saln.pdf');
        $pageCount = $pdf->setSourceFile($templatePath);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $tplIdx = $pdf->importPage($pageNo);
            $pdf->AddPage('P', [215.9, 330.2]);
            $pdf->useTemplate($tplIdx, 0, 0, 215.9);

            $pdf->SetFont('Times', '', 9);

            // === PAGE 1: Declarant Info ===
            if ($pageNo === 1) {
                // ✅ Filing Type (Checkmarks)
                $pdf->SetFont('Times', '', 8);
                $pdf->SetXY(25, 85); // position label slightly below spouse section

                // Set ZapfDingbats font for check marks
                $pdf->SetFont('ZapfDingbats', '', 9);
                $jointX = 57;
                $separateX = 95;
                $naX = 133;
                $y = 42.5;

                $filingType = strtolower($personalInfo->filing_type ?? '');

                if ($filingType === 'joint') {
                    $pdf->Text($jointX, $y, chr(52)); // ✓
                } elseif ($filingType === 'separate') {
                    $pdf->Text($separateX, $y, chr(52)); // ✓
                } elseif ($filingType === 'not_applicable') {
                    $pdf->Text($naX, $y, chr(52)); // ✓
                }
                
                // ✅ Basic name details
                $this->centerText($pdf, 52, strtoupper($personalInfo->first_name ?? ''), 52, 105);
                $this->centerText($pdf, 52, strtoupper(substr($personalInfo->middle_name ?? '', 0, 1)) . '.', 80, 130);
                $this->centerText($pdf, 52, strtoupper($personalInfo->last_name ?? ''), 20, 73);
                $this->centerText($pdf, 40, strtoupper($personalInfo->extension_name ?? ''), 170, 200);

                // ✅ Position
                $pdf->SetXY(150, 49);
                $pdf->Cell(55, 4, strtoupper($personalInfo->position ?? ''), 0, 0, 'L');


                // ✅ Address (combine all address fields with commas)
                $addressParts = [
                    $personalInfo->permanentAddress->house_block_lot_no ?? '',
                    $personalInfo->permanentAddress->street ?? '',
                    $personalInfo->permanentAddress->subdivision ?? '',
                    $personalInfo->permanentAddress->barangay ?? '',
                    $personalInfo->permanentAddress->city ?? '',
                    $personalInfo->permanentAddress->province ?? ''
                ];

                // Filter out empty parts, then join with commas
                $address = implode(', ', array_filter($addressParts, fn($part) => !empty(trim($part))));

                if (!empty($address)) {
                    $pdf->SetFont('Times', '', 8);
                    $pdf->SetXY(34, 57.5);
                    $pdf->MultiCell(83, 4, strtoupper($address), 0, 'L');
                }

                // ✅ Personal (Declarant) Agency / Office
                if (!empty($personal_agency)) {
                    if (!empty($personal_agency->name)) {
                        $pdf->SetFont('Times', '', 8);
                        $pdf->SetXY(150, 53.5); // Agency Name position
                        $pdf->MultiCell(70, 4, strtoupper($personal_agency->name), 0, 'L');
                    }

                    if (!empty($personal_agency->address)) {
                        $pdf->SetFont('Times', '', 8);
                        $pdf->SetXY(150, 57.5); // Agency Address position
                        $pdf->MultiCell(60, 4, strtoupper($personal_agency->address), 0, 'L');
                    }
                }

                // ✅ SPOUSE INFO
                if (!empty($personalInfo->spouse)) {
                    $spouse = $personalInfo->spouse;
                    
                    $this->centerText($pdf, 72, strtoupper($spouse->last_name ?? ''), 20, 73);
                    $this->centerText($pdf, 72, strtoupper($spouse->first_name ?? ''), 52, 105);
                    $this->centerText($pdf, 72, strtoupper(substr($spouse->middle_name ?? '', 0, 1)) . '.', 80, 130);
                    $pdf->SetXY(150, 69.5);
                    $pdf->SetFont('Times', '', 8);
                    $pdf->Cell(50, 4, strtoupper($spouse->position ?? ''), 0, 0, 'L');
                }

                // ✅ Spouse Agency / Office (adjust Y coordinates as needed)
                if (!empty($spouse_agency)) {
                    if (!empty($spouse_agency->name)) {
                        $pdf->SetFont('Times', '', 8);
                        $pdf->SetXY(150, 73.5); // Spouse agency name
                        $pdf->MultiCell(70, 4, strtoupper($spouse_agency->name), 0, 'L');
                    }

                    if (!empty($spouse_agency->address)) {
                        $pdf->SetFont('Times', '', 8);
                        $pdf->SetXY(150, 77.5); // Spouse agency address
                        $pdf->MultiCell(60, 4, strtoupper($spouse_agency->address), 0, 'L');
                    }
                }

                // ✅ CHILDREN (Living with Declarant)
                $children = $personalInfo->children()
                    ->where('is_living_with_declarant', true)
                    ->get();

                if ($children->isNotEmpty()) {
                    $pdf->SetFont('Times', '', 9);

                    $y = 107; // starting vertical position for children section
                    foreach ($children as $child) {
                        $name = strtoupper($child->full_name ?? '');
                        $dob = !empty($child->date_of_birth)
                            ? date('m/d/Y', strtotime($child->date_of_birth))
                            : '';

                        // Calculate age if DOB exists
                        $age = '';
                        if (!empty($child->date_of_birth)) {
                            $birthDate = new \DateTime($child->date_of_birth);
                            $today = new \DateTime();
                            $age = $today->diff($birthDate)->y; // difference in years
                        }

                        // Child Name (left side)
                        $pdf->SetXY(21, $y);
                        $pdf->Cell(80, 4, $name, 0, 0, 'C');

                        // Date of Birth (middle)
                        $pdf->SetXY(116, $y);
                        $pdf->Cell(35, 4, $dob, 0, 0, 'C');

                        // Age (right side)
                        $pdf->SetXY(170, $y);
                        $pdf->Cell(20, 4, $age, 0, 0, 'C');

                        $y += 5; // Move down for next child
                    }
                }

                // ✅ REAL PROPERTIES (Assets)
                $real_properties = $personalInfo->assetsRealProperties()
                    ->where('reporting_year', $reportingYear)
                    ->get();

                if ($real_properties->isNotEmpty()) {
                    $pdf->SetFont('Times', '', 8);

                    $y = 187; // starting Y position (adjust to match your PDF layout)

                    foreach ($real_properties as $property) {
                        $startY = $y; // remember row start position

                        $description = strtoupper($property->description ?? '');
                        $kind = strtoupper($property->kind ?? '');
                        $location = strtoupper($property->location ?? '');
                        $assessed_value = number_format($property->assessed_value ?? 0, 2);
                        $fair_value = number_format($property->current_fair_market_value ?? 0, 2);
                        $acquisition_year = strtoupper($property->acquisition_year ?? '');
                        $acquisition_mode = strtoupper($property->acquisition_mode ?? '');
                        $acquisition_cost = number_format($property->acquisition_cost ?? 0, 2);

                        // Set X positions for each column
                        $xPositions = [
                            'description' => 8,
                            'kind' => 31.7,
                            'location' => 59,
                            'assessed_value' => 98.5,
                            'fair_value' => 119,
                            'acquisition_year' => 146,
                            'acquisition_mode' => 159,
                            'acquisition_cost' => 176
                        ];

                        // Define widths for each column
                        $widths = [
                            'description' => 23.7,
                            'kind' => 27.2,
                            'location' => 39.6,
                            'assessed_value' => 20.7,
                            'fair_value' => 27.3,
                            'acquisition_year' => 12.7,
                            'acquisition_mode' => 17,
                            'acquisition_cost' => 30.3
                        ];

                        // === Draw Description (multi-line + border)
                        $pdf->SetXY($xPositions['description'], $y);
                        $pdf->MultiCell($widths['description'], 4, $description, 0, 'L');
                        $descHeight = $pdf->GetY() - $startY;

                        // Use the tallest cell height for all other cells
                        $rowHeight = max($descHeight, 5);

                        // === Draw other cells (single-line with borders)
                        $pdf->SetXY($xPositions['kind'], $startY);
                        $pdf->MultiCell($widths['kind'], 4, $kind, 0, 'L');

                        $pdf->SetXY($xPositions['location'], $startY);
                        $pdf->MultiCell($widths['location'], 4, $location, 0, 'L');

                        $pdf->SetXY($xPositions['assessed_value'], $startY);
                        $pdf->MultiCell($widths['assessed_value'], 4, $assessed_value, 0, 'R');

                        $pdf->SetXY($xPositions['fair_value'], $startY);
                        $pdf->MultiCell($widths['fair_value'], 4, $fair_value, 0, 'R');

                        $pdf->SetXY($xPositions['acquisition_year'], $startY);
                        $pdf->MultiCell($widths['acquisition_year'], 4, $acquisition_year, 0, 'C');

                        $pdf->SetXY($xPositions['acquisition_mode'], $startY);
                        $pdf->MultiCell($widths['acquisition_mode'], 4, $acquisition_mode, 0, 'C');

                        $pdf->SetXY($xPositions['acquisition_cost'], $startY);
                        $pdf->MultiCell($widths['acquisition_cost'], 4, $acquisition_cost, 0, 'R');

                        // Move down by total row height + small padding
                        $y = $startY + $rowHeight + 10;
                    }
                } 


                // ✅ PERSONAL PROPERTIES (Assets)
                $personal_properties = $personalInfo->assetsPersonalProperties()
                    ->where('reporting_year', $reportingYear)
                    ->get();

                if ($personal_properties->isNotEmpty()) {
                    $pdf->SetFont('Times', '', 8);

                    // Adjust Y position to follow after the real properties table
                    $y = $y + 47.5; // continue a bit below the last section

                    foreach ($personal_properties as $pAsset) {
                        $startY = $y;

                        $description = strtoupper($pAsset->description ?? '');
                        $year_acquired = strtoupper($pAsset->year_acquired ?? '');
                        $acquisition_cost = number_format($pAsset->acquisition_cost ?? 0, 2);

                        // Column positions (adjust according to your template’s layout)
                        $xPositions = [
                            'description' => 8,
                            'year_acquired' => 119,
                            'acquisition_cost' => 176,
                        ];

                        // Column widths
                        $widths = [
                            'description' => 111,
                            'year_acquired' => 57,
                            'acquisition_cost' => 30.4,
                        ];

                        // === Draw Description (multi-line if long)
                        $pdf->SetXY($xPositions['description'], $y);
                        $pdf->MultiCell($widths['description'], 4, $description, 0, 'L');
                        $descHeight = $pdf->GetY() - $startY;

                        // Compute row height
                        $rowHeight = max($descHeight, 5);

                        // === Year Acquired
                        $pdf->SetXY($xPositions['year_acquired'], $startY);
                        $pdf->MultiCell($widths['year_acquired'], 4, $year_acquired, 0, 'C');

                        // === Acquisition Cost
                        $pdf->SetXY($xPositions['acquisition_cost'], $startY);
                        $pdf->MultiCell($widths['acquisition_cost'], 4, $acquisition_cost, 0, 'R');

                        // Move down for next entry
                        $y = $startY + $rowHeight + 0.5;
                    }
                }

                // ✅ TOTAL COSTS SECTION
                $total_costs = $personalInfo->totalCosts()
                    ->where('reporting_year', $reportingYear)
                    ->latest()
                    ->first();

                if ($total_costs) {
                    $pdf->SetFont('Times', 'B', 9);

                    // === TOTAL REAL PROPERTIES ===
                    // (Example Y coordinate: 261 — adjust based on actual PDF alignment)
                    $pdf->SetXY(176, 239.5);
                    $pdf->Cell(31, 5, number_format($total_costs->real_properties_total ?? 0, 2), 0, 0, 'C');

                    // === TOTAL PERSONAL PROPERTIES ===
                    // (Example Y coordinate: 266)
                    $pdf->SetXY(176, 285);
                    $pdf->Cell(30, 5, number_format($total_costs->personal_property_total ?? 0, 2), 0, 0, 'C');

                    // === TOTAL ASSETS (A + B) ===
                    // (Example Y coordinate: 271)
                    $pdf->SetXY(176, 295);
                    $pdf->Cell(30, 5, number_format($total_costs->total_assets_costs ?? 0, 2), 0, 0, 'C');
                }
            }

            // === PAGE 2: Liabilities + Business Interests & Financial Connections ===
            if ($pageNo === 2) {

                // ==========================
                // 🧾 LIABILITIES SECTION
                // ==========================
                if ($liabilities->isNotEmpty()) {
                    $pdf->SetFont('Times', '', 8);

                    $y = 27; // starting Y position for liabilities
                    foreach ($liabilities as $liability) {
                        $nature_type = strtoupper($liability->nature_type ?? '');
                        $creditor = strtoupper($liability->name_of_creditors ?? '');
                        $balance = number_format($liability->outstanding_balance ?? 0, 2);

                        // Column positions
                        $xPositions = [
                            'nature_type' => 7,
                            'creditor' => 83,
                            'balance' => 165
                        ];

                        // Column widths
                        $widths = [
                            'nature_type' => 75,
                            'creditor' => 82,
                            'balance' => 41
                        ];

                        // === Nature/Type of Liability
                        $pdf->SetXY($xPositions['nature_type'], $y);
                        $pdf->MultiCell($widths['nature_type'], 4, $nature_type, 0, 'L');
                        $natureHeight = $pdf->GetY() - $y;
                        $rowHeight = max($natureHeight, 5);

                        // === Name of Creditor
                        $pdf->SetXY($xPositions['creditor'], $y);
                        $pdf->MultiCell($widths['creditor'], 4, $creditor, 0, 'C');

                        // === Outstanding Balance
                        $pdf->SetXY($xPositions['balance'], $y);
                        $pdf->MultiCell($widths['balance'], 4, $balance, 0, 'R');

                        $y += $rowHeight + 0.5; // move down for next liability
                    }
                }

                // ✅ TOTAL LIABILITIES & NET WORTH (from total_costs table)
                if (!empty($total_costs)) {
                    $pdf->SetFont('Times', 'B', 9);

                    // === TOTAL LIABILITIES ===
                    $pdf->SetXY(165, 46);
                    $pdf->Cell(42, 5, number_format($total_costs->total_liabilities ?? 0, 2), 0, 0, 'C');

                    // === NET WORTH ===
                    $pdf->SetXY(165, 52);
                    $pdf->Cell(42, 5, number_format($total_costs->net_worth ?? 0, 2), 0, 0, 'C');
                }

                // ==========================
                // 💼 BUSINESS INTERESTS & FINANCIAL CONNECTIONS
                // ==========================
                $businessInterests = $personalInfo->businessInterests()
                    ->where('reporting_year', $reportingYear ?? date('Y')) // optional safeguard
                    ->where('no_business_interest', false) // ✅ exclude checkbox-only record
                    ->get();

                if ($businessInterests->isNotEmpty()) {
                    $pdf->SetFont('Times', '', 8);

                    $y = 103; // starting Y position for Business Interests
                    foreach ($businessInterests as $interest) {
                        $name_of_business = strtoupper($interest->name_of_business ?? '');
                        $business_address = strtoupper($interest->business_address ?? '');
                        $name_of_interest = strtoupper($interest->name_of_business_interest ?? '');
                        $date_of_acquisition = !empty($interest->date_of_acquisition)
                            ? date('m/d/Y', strtotime($interest->date_of_acquisition))
                            : '';

                        // Column positions
                        $xPositions = [
                            'name_of_business' => 9.5,
                            'business_address' => 56.5,
                            'name_of_interest' => 108,
                            'date_of_acquisition' => 160,
                        ];

                        // Column widths
                        $widths = [
                            'name_of_business' => 47,
                            'business_address' => 51.5,
                            'name_of_interest' => 52,
                            'date_of_acquisition' => 46.5,
                        ];

                        // === Name of Business
                        $pdf->SetXY($xPositions['name_of_business'], $y);
                        $pdf->MultiCell($widths['name_of_business'], 4, $name_of_business, 0, 'L');
                        $heightBusiness = $pdf->GetY() - $y;
                        $rowHeight = max($heightBusiness, 5);

                        // === Business Address
                        $pdf->SetXY($xPositions['business_address'], $y);
                        $pdf->MultiCell($widths['business_address'], 4, $business_address, 0, 'C');

                        // === Nature of Business Interest / Financial Connection
                        $pdf->SetXY($xPositions['name_of_interest'], $y);
                        $pdf->MultiCell($widths['name_of_interest'], 4, $name_of_interest, 0, 'C');

                        // === Date of Acquisition
                        $pdf->SetXY($xPositions['date_of_acquisition'], $y);
                        $pdf->MultiCell($widths['date_of_acquisition'], 4, $date_of_acquisition, 0, 'C');

                        $y += $rowHeight + 0.5; // move down for next entry
                    }
                }
                // === NO BUSINESS INTEREST CHECKBOX ===
                else {
                    $hasNoBusinessInterest = $personalInfo->businessInterests()
                        ->where('no_business_interest', true)
                        ->exists();

                    if ($hasNoBusinessInterest) {
                        // ✅ Always reset font after import
                        $pdf->SetFont('ZapfDingbats', '', 8);
                        $pdf->SetXY(52.4, 83); // adjust this position for your checkbox box
                        $pdf->Cell(5, 5, '4', 0, 0, 'C'); // ✓ symbol
                    }
                }


// ==========================
// 🧑‍💼 RELATIVES IN GOVERNMENT SERVICE
// ==========================
$relativesQuery = $personalInfo->relativesInGovService();

$relatives = $relativesQuery
    ->where('no_relative_in_gov_service', false)
    ->get();

if ($relatives->isNotEmpty()) {
    $pdf->SetFont('Times', '', 8);

    $y = 155; // starting Y position for Relatives in Gov’t Service
    foreach ($relatives as $relative) {
        $name_of_relative = strtoupper($relative->name_of_relative ?? '');
        $relationship = strtoupper($relative->relationship ?? '');
        $position_of_relative = strtoupper($relative->position_of_relative ?? '');
        $name_of_agency = strtoupper($relative->name_of_agency ?? '');

        // Column positions
        $xPositions = [
            'name_of_relative' => 9.5,
            'relationship' => 62,
            'position_of_relative' => 100,
            'name_of_agency' => 130,
        ];

        // Column widths
        $widths = [
            'name_of_relative' => 52,
            'relationship' => 38,
            'position_of_relative' => 30,
            'name_of_agency' => 76,
        ];

        // === Name of Relative
        $pdf->SetXY($xPositions['name_of_relative'], $y);
        $pdf->MultiCell($widths['name_of_relative'], 4, $name_of_relative, 0, 'L');
        $heightRelative = $pdf->GetY() - $y;
        $rowHeight = max($heightRelative, 5);

        // === Relationship
        $pdf->SetXY($xPositions['relationship'], $y);
        $pdf->MultiCell($widths['relationship'], 4, $relationship, 0, 'C');

        // === Position of Relative
        $pdf->SetXY($xPositions['position_of_relative'], $y);
        $pdf->MultiCell($widths['position_of_relative'], 4, $position_of_relative, 0, 'C');

        // === Name of Agency
        $pdf->SetXY($xPositions['name_of_agency'], $y);
        $pdf->MultiCell($widths['name_of_agency'], 4, $name_of_agency, 0, 'C');

        $y += $rowHeight + 0;
    }
} else {
    // === NO RELATIVE IN GOV’T SERVICE CHECKBOX ===
    $hasNoRelative = $personalInfo->relativesInGovService()
        ->where('no_relative_in_gov_service', true)
        ->exists();

    if ($hasNoRelative) {
        $pdf->SetFont('ZapfDingbats', '', 8);
        $pdf->SetXY(53.5, 143.2); // adjust Y/X for your checkbox
        $pdf->Cell(5, 5, '4', 0, 0, 'C');
    }
}


            }



        }

        // ✅ Output file
        $filename = 'SALN_' . strtoupper($personalInfo->last_name ?? 'USER') . '_' . $reportingYear . '.pdf';
        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
