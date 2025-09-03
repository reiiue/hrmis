<!-- resources/views/pds/educational_background.blade.php -->

        <tr>
            <td colspan="62" class="pds-section-header">III. EDUCATIONAL BACKGROUND</td>
        </tr>
        <tr>
            <td rowspan="2" colspan="6" class="pds-label text-center align-middle">LEVEL</td>
            <td rowspan="2" colspan="15" class="pds-label text-center align-middle">NAME OF SCHOOL <br> <small>(Write in full)</small></td>
            <td rowspan="2" colspan="15" class="pds-label text-center align-middle">BASIC EDUCATION/ <br> DEGREE/COURSE <br> <small>(Write in full)</small></td>
            <td colspan="10" class="pds-label text-center">PERIOD OF ATTENDANCE</td>
            <td rowspan="2" colspan="5" class="pds-label text-center align-middle">HIGHEST LEVEL/ <br> UNITS EARNED <br> <small>(if not graduated)</small></td>
            <td rowspan="2" colspan="6" class="pds-label text-center align-middle">YEAR GRADUATED</td>
            <td rowspan="2" colspan="5" class="pds-label " style="font-size: 8px">SCHOLARSHIP <br> ACADEMIC HONORS <br> RECEIVED</td>
        </tr>
        <tr>
            <td colspan="5" class="pds-label text-center">From</td>
            <td colspan="5" class="pds-label text-center">To</td>
        </tr>

{{-- Elementary --}}
<tr>
    <td colspan="6" class="pds-label" style="font-size: 10px">ELEMENTARY</td>

    {{-- School Name --}}
    <td colspan="15">
        <input type="text" 
               name="elementary_school" 
               class="form-control pds-input-borderless"
               value="{{ old('elementary_school', optional($educationalBackgrounds->where('level', 'Elementary')->first())->school_name) }}">
    </td>

    {{-- Degree --}}
    <td colspan="15">
        <input type="text" 
               name="elementary_degree" 
               class="form-control pds-input-borderless"
               value="{{ old('elementary_degree', optional($educationalBackgrounds->where('level', 'Elementary')->first())->degree_course) }}">
    </td>

    {{-- Period (From) --}}
    <td colspan="5">
        <input type="number" 
               name="elementary_period_from" 
               class="form-control pds-input-borderless"
               value="{{ old('elementary_period_from', optional($educationalBackgrounds->where('level', 'Elementary')->first())->period_from) }}">
    </td>

    {{-- Period (To) --}}
    <td colspan="5">
        <input type="number" 
               name="elementary_period_to" 
               class="form-control pds-input-borderless"
               value="{{ old('elementary_period_to', optional($educationalBackgrounds->where('level', 'Elementary')->first())->period_to) }}">
    </td>

    {{-- Highest Level --}}
    <td colspan="5">
        <input type="text" 
               name="elementary_highest_level" 
               class="form-control pds-input-borderless"
               value="{{ old('elementary_highest_level', optional($educationalBackgrounds->where('level', 'Elementary')->first())->highest_level_unit_earned) }}">
    </td>

    {{-- Year Graduated --}}
    <td colspan="6">
        <input type="number" 
               name="elementary_year_graduated" 
               class="form-control pds-input-borderless"
               value="{{ old('elementary_year_graduated', optional($educationalBackgrounds->where('level', 'Elementary')->first())->year_graduated) }}">
    </td>

    {{-- Honors --}}
    <td colspan="5">
        <input type="text" 
               name="elementary_honors" 
               class="form-control pds-input-borderless"
               value="{{ old('elementary_honors', optional($educationalBackgrounds->where('level', 'Elementary')->first())->scholarship_honors) }}">
    </td>
</tr>

{{-- Hidden Level Field --}}
<input type="hidden" name="elementary_level" value="Elementary">


{{-- Secondary --}}
<tr>
    <td colspan="6" class="pds-label" style="font-size: 10px">SECONDARY</td>

    <td colspan="15">
        <input type="text" 
               name="secondary_school" 
               class="form-control pds-input-borderless"
               value="{{ old('secondary_school', optional($educationalBackgrounds->where('level', 'Secondary')->first())->school_name) }}">
    </td>

    <td colspan="15">
        <input type="text" 
               name="secondary_degree" 
               class="form-control pds-input-borderless"
               value="{{ old('secondary_degree', optional($educationalBackgrounds->where('level', 'Secondary')->first())->degree_course) }}">
    </td>

    <td colspan="5">
        <input type="number" 
               name="secondary_period_from" 
               class="form-control pds-input-borderless"
               value="{{ old('secondary_period_from', optional($educationalBackgrounds->where('level', 'Secondary')->first())->period_from) }}">
    </td>

    <td colspan="5">
        <input type="number" 
               name="secondary_period_to" 
               class="form-control pds-input-borderless"
               value="{{ old('secondary_period_to', optional($educationalBackgrounds->where('level', 'Secondary')->first())->period_to) }}">
    </td>

    <td colspan="5">
        <input type="text" 
               name="secondary_highest_level" 
               class="form-control pds-input-borderless"
               value="{{ old('secondary_highest_level', optional($educationalBackgrounds->where('level', 'Secondary')->first())->highest_level_unit_earned) }}">
    </td>

    <td colspan="6">
        <input type="number" 
               name="secondary_year_graduated" 
               class="form-control pds-input-borderless"
               value="{{ old('secondary_year_graduated', optional($educationalBackgrounds->where('level', 'Secondary')->first())->year_graduated) }}">
    </td>

    <td colspan="5">
        <input type="text" 
               name="secondary_honors" 
               class="form-control pds-input-borderless"
               value="{{ old('secondary_honors', optional($educationalBackgrounds->where('level', 'Secondary')->first())->scholarship_honors) }}">
    </td>
</tr>

{{-- Hidden Level Field --}}
<input type="hidden" name="secondary_level" value="Secondary">



{{-- Vocational / Trade Course --}}
<tr>
    <td colspan="6" class="pds-label" style="font-size: 10px">VOCATIONAL / TRADE COURSE</td>

    {{-- School Name --}}
    <td colspan="15">
        <input type="text" 
               name="vocational_school" 
               class="form-control pds-input-borderless"
               value="{{ old('vocational_school', optional($educationalBackgrounds->where('level', 'VocationalCourse')->first())->school_name) }}">
    </td>

    {{-- Degree --}}
    <td colspan="15">
        <input type="text" 
               name="vocational_degree" 
               class="form-control pds-input-borderless"
               value="{{ old('vocational_degree', optional($educationalBackgrounds->where('level', 'VocationalCourse')->first())->degree_course) }}">
    </td>

    {{-- Period (From) --}}
    <td colspan="5">
        <input type="number" 
               name="vocational_period_from" 
               class="form-control pds-input-borderless"
               value="{{ old('vocational_period_from', optional($educationalBackgrounds->where('level', 'VocationalCourse')->first())->period_from) }}">
    </td>

    {{-- Period (To) --}}
    <td colspan="5">
        <input type="number" 
               name="vocational_period_to" 
               class="form-control pds-input-borderless"
               value="{{ old('vocational_period_to', optional($educationalBackgrounds->where('level', 'VocationalCourse')->first())->period_to) }}">
    </td>

    {{-- Highest Level --}}
    <td colspan="5">
        <input type="text" 
               name="vocational_highest_level" 
               class="form-control pds-input-borderless"
               value="{{ old('vocational_highest_level', optional($educationalBackgrounds->where('level', 'VocationalCourse')->first())->highest_level_unit_earned) }}">
    </td>

    {{-- Year Graduated --}}
    <td colspan="6">
        <input type="number" 
               name="vocational_year_graduated" 
               class="form-control pds-input-borderless"
               value="{{ old('vocational_year_graduated', optional($educationalBackgrounds->where('level', 'VocationalCourse')->first())->year_graduated) }}">
    </td>

    {{-- Honors --}}
    <td colspan="5">
        <input type="text" 
               name="vocational_honors" 
               class="form-control pds-input-borderless"
               value="{{ old('vocational_honors', optional($educationalBackgrounds->where('level', 'VocationalCourse')->first())->scholarship_honors) }}">
    </td>
</tr>

{{-- Hidden Level Field --}}
<input type="hidden" name="vocational_level" value="VocationalCourse">


{{-- College --}}
<tr>
    <td colspan="6" class="pds-label" style="font-size: 10px">COLLEGE</td>

    {{-- School Name --}}
    <td colspan="15">
        <input type="text" 
               name="college_school" 
               class="form-control pds-input-borderless"
               value="{{ old('college_school', optional($educationalBackgrounds->where('level', 'College')->first())->school_name) }}">
    </td>

    {{-- Degree --}}
    <td colspan="15">
        <input type="text" 
               name="college_degree" 
               class="form-control pds-input-borderless"
               value="{{ old('college_degree', optional($educationalBackgrounds->where('level', 'College')->first())->degree_course) }}">
    </td>

    {{-- Period (From) --}}
    <td colspan="5">
        <input type="number" 
               name="college_period_from" 
               class="form-control pds-input-borderless"
               value="{{ old('college_period_from', optional($educationalBackgrounds->where('level', 'College')->first())->period_from) }}">
    </td>

    {{-- Period (To) --}}
    <td colspan="5">
        <input type="number" 
               name="college_period_to" 
               class="form-control pds-input-borderless"
               value="{{ old('college_period_to', optional($educationalBackgrounds->where('level', 'College')->first())->period_to) }}">
    </td>

    {{-- Highest Level --}}
    <td colspan="5">
        <input type="text" 
               name="college_highest_level" 
               class="form-control pds-input-borderless"
               value="{{ old('college_highest_level', optional($educationalBackgrounds->where('level', 'College')->first())->highest_level_unit_earned) }}">
    </td>

    {{-- Year Graduated --}}
    <td colspan="6">
        <input type="number" 
               name="college_year_graduated" 
               class="form-control pds-input-borderless"
               value="{{ old('college_year_graduated', optional($educationalBackgrounds->where('level', 'College')->first())->year_graduated) }}">
    </td>

    {{-- Honors --}}
    <td colspan="5">
        <input type="text" 
               name="college_honors" 
               class="form-control pds-input-borderless"
               value="{{ old('college_honors', optional($educationalBackgrounds->where('level', 'College')->first())->scholarship_honors) }}">
    </td>
</tr>

{{-- Hidden Level Field --}}
<input type="hidden" name="college_level" value="College">


{{-- Graduate Studies --}}
<tr>
    <td colspan="6" class="pds-label" style="font-size: 10px">GRADUATE STUDIES</td>

    {{-- School Name --}}
    <td colspan="15">
        <input type="text" 
               name="graduate_school" 
               class="form-control pds-input-borderless"
               value="{{ old('graduate_school', optional($educationalBackgrounds->where('level', 'GraduateStudies')->first())->school_name) }}">
    </td>

    {{-- Degree --}}
    <td colspan="15">
        <input type="text" 
               name="graduate_degree" 
               class="form-control pds-input-borderless"
               value="{{ old('graduate_degree', optional($educationalBackgrounds->where('level', 'Graduate Studies')->first())->degree_course) }}">
    </td>

    {{-- Period (From) --}}
    <td colspan="5">
        <input type="number" 
               name="graduate_period_from" 
               class="form-control pds-input-borderless"
               value="{{ old('graduate_period_from', optional($educationalBackgrounds->where('level', 'Graduate Studies')->first())->period_from) }}">
    </td>

    {{-- Period (To) --}}
    <td colspan="5">
        <input type="number" 
               name="graduate_period_to" 
               class="form-control pds-input-borderless"
               value="{{ old('graduate_period_to', optional($educationalBackgrounds->where('level', 'Graduate Studies')->first())->period_to) }}">
    </td>

    {{-- Highest Level --}}
    <td colspan="5">
        <input type="text" 
               name="graduate_highest_level" 
               class="form-control pds-input-borderless"
               value="{{ old('graduate_highest_level', optional($educationalBackgrounds->where('level', 'Graduate Studies')->first())->highest_level_unit_earned) }}">
    </td>

    {{-- Year Graduated --}}
    <td colspan="6">
        <input type="number" 
               name="graduate_year_graduated" 
               class="form-control pds-input-borderless"
               value="{{ old('graduate_year_graduated', optional($educationalBackgrounds->where('level', 'Graduate Studies')->first())->year_graduated) }}">
    </td>

    {{-- Honors --}}
    <td colspan="5">
        <input type="text" 
               name="graduate_honors" 
               class="form-control pds-input-borderless"
               value="{{ old('graduate_honors', optional($educationalBackgrounds->where('level', 'Graduate Studies')->first())->scholarship_honors) }}">
    </td>
</tr>

{{-- Hidden Level Field --}}
<input type="hidden" name="graduate_level" value="GraduateStudies">
