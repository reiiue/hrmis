    <!-- resources/views/pds/work_experience.blade.php -->

    <tr>
        <td colspan="62" class="pds-section-header">V. WORK EXPERIENCE</td>
    </tr>
    <tr>
        <td colspan="62" class="pds-label" style="font-size:10px;">
            (Include private employment. Start from your recent work) <br>
            Description of duties should be indicated in the attached Work Experience sheet.
        </td>
    </tr>
    <tr>
        {{-- Inclusive Dates --}}
        <td colspan="12" class="pds-label text-center align-middle" style="font-size:10px;">
            INCLUSIVE DATES <br> <small>(mm/dd/yyyy)</small>
        </td>

        {{-- Position Title --}}
        <td rowspan="2" colspan="14" class="pds-label text-center align-middle" style="font-size:10px;">
            POSITION TITLE <br> <small>(Write in full / Do not abbreviate)</small>
        </td>

        {{-- Department / Agency --}}
        <td rowspan="2" colspan="14" class="pds-label text-center align-middle" style="font-size:10px;">
            DEPARTMENT / AGENCY / OFFICE / COMPANY <br> <small>(Write in full / Do not abbreviate)</small>
        </td>

        {{-- Monthly Salary --}}
        <td rowspan="2" colspan="7" class="pds-label text-center align-middle" style="font-size:10px;">
            MONTHLY SALARY
        </td>

        {{-- Salary/Job/Pay Grade --}}
        <td rowspan="2" colspan="5" class="pds-label text-center align-middle" style="font-size:10px;">
            SALARY/ JOB/ PAY GRADE & STEP <br> <small>(Format "00-0") / INCREMENT</small>
        </td>

        {{-- Status of Appointment --}}
        <td rowspan="2" colspan="6" class="pds-label text-center align-middle" style="font-size:10px;">
            STATUS OF APPOINTMENT
        </td>

        {{-- Gov’t Service --}}
        <td rowspan="2" colspan="4" class="pds-label text-center align-middle" style="font-size:10px;">
            GOV'T SERVICE <br> <small>(Y/N)</small>
        </td>
    </tr>
    <tr>
        <td colspan="6" class="pds-label text-center align-middle" style="font-size:10px;">FROM</td>
        <td colspan="6" class="pds-label text-center align-middle" style="font-size:10px;">TO</td>
    </tr>

    {{-- Dynamic Rows --}}
    <tbody id="workExperienceTable">
        @foreach($work_experiences ?? [] as $index => $work)
        <tr>
            {{-- Inclusive Dates From --}}
            <td colspan="6">
                <input type="date" 
                    name="inclusive_date_from[]" 
                    class="form-control pds-input-borderless"
                    value="{{ old('inclusive_date_from.' . $index, $work->inclusive_date_from) }}">
            </td>

            {{-- Inclusive Dates To --}}
            <td colspan="6">
                <input type="date" 
                    name="inclusive_date_to[]" 
                    class="form-control pds-input-borderless"
                    value="{{ old('inclusive_date_to.' . $index, $work->inclusive_date_to) }}">
            </td>

            {{-- Position Title --}}
            <td colspan="14">
                <input type="text" 
                    name="position_title[]" 
                    class="form-control pds-input-borderless"
                    value="{{ old('position_title.' . $index, $work->position_title) }}">
            </td>

            {{-- Department/Agency --}}
            <td colspan="14">
                <input type="text" 
                    name="department_agency[]" 
                    class="form-control pds-input-borderless"
                    value="{{ old('department_agency.' . $index, $work->department_agency) }}">
            </td>

            {{-- Monthly Salary --}}
            <td colspan="7">
                <input type="number" 
                    name="monthly_salary[]" 
                    class="form-control pds-input-borderless"
                    value="{{ old('monthly_salary.' . $index, $work->monthly_salary) }}">
            </td>

            {{-- Salary Grade Step --}}
            <td colspan="5">
                <input type="text" 
                    name="salary_grade_step[]" 
                    class="form-control pds-input-borderless"
                    value="{{ old('salary_grade_step.' . $index, $work->salary_grade_step) }}">
            </td>

            {{-- Status of Appointment --}}
            <td colspan="6">
                <input type="text" 
                    name="status_appointment[]" 
                    class="form-control pds-input-borderless"
                    value="{{ old('status_appointment.' . $index, $work->status_appointment) }}">
            </td>

            {{-- Gov’t Service --}}
            <td colspan="4">
                <select name="gov_service[]" class="form-control pds-input-borderless">
                    <option value="Y" {{ old('gov_service.' . $index, $work->gov_service) == 'Y' ? 'selected' : '' }}>Y</option>
                    <option value="N" {{ old('gov_service.' . $index, $work->gov_service) == 'N' ? 'selected' : '' }}>N</option>
                </select>
            </td>
        </tr>
        @endforeach
    </tbody>

    {{-- Add/Remove Buttons --}}
    <tr>
        <td colspan="62" class="text-end">
            <button type="button" class="btn btn-sm btn-success" onclick="addWorkRow()">+ Add</button>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeWorkRow()">- Remove</button>
        </td>
    </tr>


    <script>
        function addWorkRow() {
            let table = document.getElementById('workExperienceTable');
            let newRow = `
            <tr>
                <td colspan="6"><input type="date" name="inclusive_date_from[]" class="form-control pds-input-borderless"></td>
                <td colspan="6"><input type="date" name="inclusive_date_to[]" class="form-control pds-input-borderless"></td>
                <td colspan="14"><input type="text" name="position_title[]" class="form-control pds-input-borderless"></td>
                <td colspan="14"><input type="text" name="department_agency[]" class="form-control pds-input-borderless"></td>
                <td colspan="7"><input type="number" name="monthly_salary[]" class="form-control pds-input-borderless"></td>
                <td colspan="5"><input type="text" name="salary_grade_step[]" class="form-control pds-input-borderless"></td>
                <td colspan="6"><input type="text" name="status_appointment[]" class="form-control pds-input-borderless"></td>
                <td colspan="4">
                    <select name="gov_service[]" class="form-control pds-input-borderless">
                        <option value="Y">Y</option>
                        <option value="N">N</option>
                    </select>
                </td>
            </tr>`;
            table.insertAdjacentHTML('beforeend', newRow);
        }

        function removeWorkRow() {
            let table = document.getElementById('workExperienceTable');
            if (table.rows.length > 0) {
                table.deleteRow(-1);
            }
        }
    </script>
