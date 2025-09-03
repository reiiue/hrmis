<?php

namespace App\Http\Controllers;

use setasign\Fpdi\Fpdi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PersonalInformation;

class PDFController extends Controller
{
    public function download()
    {
        $filePath = public_path('pdfs/pds.pdf'); // Path to PDS PDF

        $pdf = new Fpdi();

        // Get data from database
        $user = Auth::user();
        $personalInfo = PersonalInformation::where('user_id', $user->id)->first();

        $last_name   = $personalInfo?->last_name ?? '';
        $first_name  = $personalInfo?->first_name ?? '';
        $middle_name = $personalInfo?->middle_name ?? '';
        $suffix      = $personalInfo?->suffix ?? '';
        $date_of_birth = $personalInfo?->date_of_birth ?? '';
        $place_of_birth = $personalInfo?->place_of_birth ?? '';
        $sex         = $personalInfo?->sex ?? '';
        $civil_status = $personalInfo?->civil_status ?? '';

        // Get total number of pages
        $pageCount = $pdf->setSourceFile($filePath);

        // Loop through each page
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $pdf->AddPage();
            $templateId = $pdf->importPage($pageNo);
            $pdf->useTemplate($templateId);

            // Add text only to the first page
            if ($pageNo === 1) {
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetTextColor(0, 0, 0);

                // Adjust coordinates (X, Y) to match PDS fields
                $pdf->Text(55, 41.5, $last_name);
                $pdf->Text(55, 47, $first_name);
                $pdf->Text(55, 52, $middle_name);
                $pdf->Text(180, 47, $suffix);
                $pdf->Text(55, 59, $date_of_birth);
                $pdf->Text(55, 66, $place_of_birth);

                // Sex Checkmarks
                $pdf->SetFont('ZapfDingbats', '', 7); 
                if (strtolower($sex) === 'male') {
                    $pdf->Text(55, 71.5, chr(52)); 
                } elseif (strtolower($sex) === 'female') {
                    $pdf->Text(79.5, 71.5, chr(52)); 
                }

                // Civil Status Checkmarks
                $pdf->SetFont('ZapfDingbats', '', 7); 
                if (strtolower($civil_status) === 'single') {
                    $pdf->Text(55, 76.5, chr(52)); 
                } elseif (strtolower($civil_status) === 'married') {
                    $pdf->Text(79.5, 76.5, chr(52));
                } elseif (strtolower($civil_status) === 'widowed') {
                    $pdf->Text(55, 80, chr(52)); 
                } elseif (strtolower($civil_status) === 'separated') {
                    $pdf->Text(79.5, 80, chr(52)); 
                } 

            }
        }

        // Output for browser view
        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="full_pds.pdf"');
    } 
}
