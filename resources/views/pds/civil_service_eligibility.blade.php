<!-- resources/views/pds/civil_service_eligibility.blade.php -->

<tr>
    <td colspan="62" class="pds-section-header">IV. CIVIL SERVICE ELIGIBILITY</td>
</tr>
<tr>
    <td rowspan="2" colspan="20" style="font-size:10px;" class="pds-label text-center align-middle">
        CAREER SERVICE/ RA 1080 (BOARD/ BAR) <br>
        UNDER SPECIAL LAWS/ CES/ CSEE <br>
        BARANGAY ELIGIBILITY / DRIVER'S LICENSE
    </td>
    <td rowspan="2" colspan="5" style="font-size:10px;" class="pds-label text-center align-middle">
        RATING <br> <small>(If Applicable)</small>
    </td>
    <td rowspan="2" colspan="8" style="font-size:10px;" class="pds-label text-center align-middle">
        DATE OF EXAMINATION / CONFERMENT
    </td>
    <td rowspan="2" colspan="16" style="font-size:10px;" class="pds-label text-center align-middle">
        PLACE OF EXAMINATION / CONFERMENT
    </td>
    <td colspan="13" style="font-size:10px;" class="pds-label text-center align-middle">
        LICENSE (if applicable)
    </td>
</tr>
<tr>
    <td colspan="6" style="font-size:10px;" class="pds-label text-center align-middle">NUMBER</td>
    <td colspan="7" style="font-size:10px;" class="pds-label text-center align-middle">Date of Validity</td>
</tr>

{{-- Dynamic Rows --}}
<tbody id="eligibilityTable">
    @foreach($eligibilities ?? [] as $eligibility)
    <tr>
        {{-- Hidden ID field to keep existing record reference --}}
        <input type="hidden" name="eligibility_id[]" value="{{ $eligibility->id }}">

        {{-- Eligibility Type --}}
        <td colspan="20">
            <input type="text" 
                   name="eligibility_type[]" 
                   class="form-control pds-input-borderless"
                   value="{{ old('eligibility_type.' . $eligibility->id, $eligibility->eligibility_type) }}">
        </td>

        {{-- Rating --}}
        <td colspan="5">
            <input type="text" 
                   name="rating[]" 
                   class="form-control pds-input-borderless"
                   value="{{ old('rating.' . $eligibility->id, $eligibility->rating) }}">
        </td>

        {{-- Date of Examination --}}
        <td colspan="8">
            <input type="date" 
                   name="exam_date[]" 
                   class="form-control pds-input-borderless"
                   value="{{ old('exam_date.' . $eligibility->id, $eligibility->exam_date) }}">
        </td>

        {{-- Place of Examination --}}
        <td colspan="16">
            <input type="text" 
                   name="exam_place[]" 
                   class="form-control pds-input-borderless"
                   value="{{ old('exam_place.' . $eligibility->id, $eligibility->exam_place) }}">
        </td>

        {{-- License Number --}}
        <td colspan="6">
            <input type="text" 
                   name="license_number[]" 
                   class="form-control pds-input-borderless"
                   value="{{ old('license_number.' . $eligibility->id, $eligibility->license_number) }}">
        </td>

        {{-- Date of Validity --}}
        <td colspan="7">
            <input type="date" 
                   name="license_validity[]" 
                   class="form-control pds-input-borderless"
                   value="{{ old('license_validity.' . $eligibility->id, $eligibility->license_validity) }}">
        </td>
    </tr>
    @endforeach
</tbody>

{{-- Add/Remove Buttons --}}
<tr>
    <td colspan="62" class="text-end">
        <button type="button" class="btn btn-sm btn-success" onclick="addEligibilityRow()">+ Add</button>
        <button type="button" class="btn btn-sm btn-danger" onclick="removeEligibilityRow()">- Remove</button>
    </td>
</tr>


<script>
    function addEligibilityRow() {
        let table = document.getElementById('eligibilityTable');
        let newRow = `
        <tr>
            <input type="hidden" name="eligibility_id[]" value="">
            <td colspan="20"><input type="text" name="eligibility_type[]" class="form-control pds-input-borderless"></td>
            <td colspan="5"><input type="text" name="rating[]" class="form-control pds-input-borderless"></td>
            <td colspan="8"><input type="date" name="exam_date[]" class="form-control pds-input-borderless"></td>
            <td colspan="16"><input type="text" name="exam_place[]" class="form-control pds-input-borderless"></td>
            <td colspan="6"><input type="text" name="license_number[]" class="form-control pds-input-borderless"></td>
            <td colspan="7"><input type="date" name="license_validity[]" class="form-control pds-input-borderless"></td>
        </tr>`;
        table.insertAdjacentHTML('beforeend', newRow);
    }

    function removeEligibilityRow() {
        let table = document.getElementById('eligibilityTable');
        if (table.rows.length > 0) {
            table.deleteRow(-1);
        }
    }
</script>
