{{-- resources/views/pds/learning_development.blade.php --}}

<tr>
    <td colspan="62" class="pds-section-header">
        VII. LEARNING AND DEVELOPMENT (L&D) INTERVENTIONS/TRAINING PROGRAMS ATTENDED
    </td>
</tr>
<tr>
    <td colspan="62" class="pds-label" style="font-size:10px;">
        (Write in full the title of learning and development interventions/training programs. Start from your recent training.)
    </td>
</tr>
<tr>
    {{-- Training Title --}}
    <td rowspan="2" colspan="22" class="pds-label text-center align-middle" style="font-size:10px;">
        TITLE OF LEARNING AND DEVELOPMENT INTERVENTIONS/TRAINING PROGRAMS <br> <small>(Write in full)</small>
    </td>

    {{-- Inclusive Dates --}}
    <td colspan="12" class="pds-label text-center align-middle" style="font-size:10px;">
        INCLUSIVE DATES OF ATTENDANCE <br> <small>(mm/dd/yyyy)</small>
    </td>

    {{-- Number of Hours --}}
    <td rowspan="2" colspan="8" class="pds-label text-center align-middle" style="font-size:10px;">
        NUMBER OF HOURS
    </td>

    {{-- Type of LD --}}
    <td rowspan="2" colspan="10" class="pds-label text-center align-middle" style="font-size:10px;">
        TYPE OF LD <br> <small>(Managerial / Supervisory / Technical, etc.)</small>
    </td>

    {{-- Conducted By --}}
    <td rowspan="2" colspan="10" class="pds-label text-center align-middle" style="font-size:10px;">
        CONDUCTED / SPONSORED BY <br> <small>(Write in full)</small>
    </td>
</tr>
<tr>
    <td colspan="6" class="pds-label text-center align-middle" style="font-size:10px;">FROM</td>
    <td colspan="6" class="pds-label text-center align-middle" style="font-size:10px;">TO</td>
</tr>

{{-- Dynamic Rows --}}
<tbody id="learningDevelopmentTable">
    @forelse($learning_developments ?? [] as $index => $ld)
    <tr>
        {{-- Training Title (with hidden ID) --}}
        <td colspan="22">
            <input type="hidden" name="learning_development_id[]" value="{{ $ld->id }}">
            <input type="text"
                name="training_title[]"
                class="form-control pds-input-borderless"
                value="{{ old('training_title.' . $index, $ld->training_title) }}">
        </td>

        {{-- Inclusive Date From --}}
        <td colspan="6">
            <input type="date"
                name="inclusive_date_from[]"
                class="form-control pds-input-borderless"
                value="{{ old('inclusive_date_from.' . $index, $ld->inclusive_date_from) }}">
        </td>

        {{-- Inclusive Date To --}}
        <td colspan="6">
            <input type="date"
                name="inclusive_date_to[]"
                class="form-control pds-input-borderless"
                value="{{ old('inclusive_date_to.' . $index, $ld->inclusive_date_to) }}">
        </td>

        {{-- Number of Hours --}}
        <td colspan="8">
            <input type="number"
                name="number_of_hours[]"
                class="form-control pds-input-borderless"
                value="{{ old('number_of_hours.' . $index, $ld->number_of_hours) }}">
        </td>

        {{-- Type of LD --}}
        <td colspan="10">
            <input type="text"
                name="type_of_id[]"
                class="form-control pds-input-borderless"
                value="{{ old('type_of_id.' . $index, $ld->type_of_id) }}">
        </td>

        {{-- Conducted By --}}
        <td colspan="10">
            <input type="text"
                name="conducted_by[]"
                class="form-control pds-input-borderless"
                value="{{ old('conducted_by.' . $index, $ld->conducted_by) }}">
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="22">
            <input type="hidden" name="learning_development_id[]" value="">
            <input type="text" name="training_title[]" class="form-control pds-input-borderless">
        </td>
        <td colspan="6"><input type="date" name="inclusive_date_from[]" class="form-control pds-input-borderless"></td>
        <td colspan="6"><input type="date" name="inclusive_date_to[]" class="form-control pds-input-borderless"></td>
        <td colspan="8"><input type="number" name="number_of_hours[]" class="form-control pds-input-borderless"></td>
        <td colspan="10"><input type="text" name="type_of_id[]" class="form-control pds-input-borderless"></td>
        <td colspan="10"><input type="text" name="conducted_by[]" class="form-control pds-input-borderless"></td>
    </tr>
    @endforelse
</tbody>

{{-- Add/Remove Buttons --}}
<tr>
    <td colspan="62" class="text-end">
        <button type="button" class="btn btn-sm btn-success" onclick="addLearningDevelopmentRow()">+ Add</button>
        <button type="button" class="btn btn-sm btn-danger" onclick="removeLearningDevelopmentRow()">- Remove</button>
    </td>
</tr>

<script>
    function addLearningDevelopmentRow() {
        let table = document.getElementById('learningDevelopmentTable');
        let newRow = `
        <tr>
            <td colspan="22">
                <input type="hidden" name="learning_development_id[]" value="">
                <input type="text" name="training_title[]" class="form-control pds-input-borderless">
            </td>
            <td colspan="6"><input type="date" name="inclusive_date_from[]" class="form-control pds-input-borderless"></td>
            <td colspan="6"><input type="date" name="inclusive_date_to[]" class="form-control pds-input-borderless"></td>
            <td colspan="8"><input type="number" name="number_of_hours[]" class="form-control pds-input-borderless"></td>
            <td colspan="10"><input type="text" name="type_of_id[]" class="form-control pds-input-borderless"></td>
            <td colspan="10"><input type="text" name="conducted_by[]" class="form-control pds-input-borderless"></td>
        </tr>`;
        table.insertAdjacentHTML('beforeend', newRow);
    }

    function removeLearningDevelopmentRow() {
        let table = document.getElementById('learningDevelopmentTable');
        if (table.rows.length > 0) {
            table.deleteRow(-1);
        }
    }
</script>
