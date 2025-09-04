<tr>
    <td colspan="62" class="pds-section-header">
        VI. VOLUNTARY WORK OR INVOLVEMENT IN CIVIC / NON-GOVERNMENT / PEOPLE / VOLUNTARY ORGANIZATION/S
    </td>
</tr>
<tr>
    <td colspan="62" class="pds-label" style="font-size:10px;">
        (Write in full the name & address of organization. Start from your recent involvement.)
    </td>
</tr>
<tr>
    {{-- Organization Name & Address --}}
    <td rowspan="2" colspan="24" class="pds-label text-center align-middle" style="font-size:10px;">
        NAME & ADDRESS OF ORGANIZATION <br> <small>(Write in full)</small>
    </td>

    {{-- Inclusive Dates --}}
    <td colspan="12" class="pds-label text-center align-middle" style="font-size:10px;">
        INCLUSIVE DATES <br> <small>(mm/dd/yyyy)</small>
    </td>

    {{-- Number of Hours --}}
    <td rowspan="2" colspan="8" class="pds-label text-center align-middle" style="font-size:10px;">
        NUMBER OF HOURS
    </td>

    {{-- Position / Nature of Work --}}
    <td rowspan="2" colspan="18" class="pds-label text-center align-middle" style="font-size:10px;">
        POSITION / NATURE OF WORK
    </td>
</tr>
<tr>
    <td colspan="6" class="pds-label text-center align-middle" style="font-size:10px;">FROM</td>
    <td colspan="6" class="pds-label text-center align-middle" style="font-size:10px;">TO</td>
</tr>

{{-- Dynamic Rows --}}
<tbody id="membershipTable">
    @forelse($membership_associations ?? [] as $index => $membership)
    <tr>
        <td colspan="24">
            <input type="hidden"
                   name="membership_association_id[]"
                   value="{{ old('membership_association_id.' . $index, $membership->id) }}">
            <input type="text"
                   name="organization_name[]"
                   class="form-control pds-input-borderless"
                   value="{{ old('organization_name.' . $index, $membership->organization_name) }}">
        </td>

        <td colspan="6">
            <input type="date"
                   name="period_from[]"
                   class="form-control pds-input-borderless"
                   value="{{ old('period_from.' . $index, $membership->period_from) }}">
        </td>

        <td colspan="6">
            <input type="date"
                   name="period_to[]"
                   class="form-control pds-input-borderless"
                   value="{{ old('period_to.' . $index, $membership->period_to) }}">
        </td>

        <td colspan="8">
            <input type="number"
                   name="number_of_hours[]"
                   class="form-control pds-input-borderless"
                   value="{{ old('number_of_hours.' . $index, $membership->number_of_hours) }}">
        </td>

        <td colspan="18">
            <input type="text"
                   name="position[]"
                   class="form-control pds-input-borderless"
                   value="{{ old('position.' . $index, $membership->position) }}">
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="24">
            <input type="hidden" name="membership_association_id[]" value="">
            <input type="text" name="organization_name[]" class="form-control pds-input-borderless">
        </td>
        <td colspan="6"><input type="date" name="period_from[]" class="form-control pds-input-borderless"></td>
        <td colspan="6"><input type="date" name="period_to[]" class="form-control pds-input-borderless"></td>
        <td colspan="8"><input type="number" name="number_of_hours[]" class="form-control pds-input-borderless"></td>
        <td colspan="18"><input type="text" name="position[]" class="form-control pds-input-borderless"></td>
    </tr>
    @endforelse
</tbody>



{{-- Add/Remove Buttons --}}
<tr>
    <td colspan="62" class="text-end">
        <button type="button" class="btn btn-sm btn-success" onclick="addMembershipRow()">+ Add</button>
        <button type="button" class="btn btn-sm btn-danger" onclick="removeMembershipRow()">- Remove</button>
    </td>
</tr>

<script>
    function addMembershipRow() {
        let table = document.getElementById('membershipTable');
        let newRow = `
        <tr>
            <td colspan="24">
                <input type="hidden" name="membership_association_id[]" value="">
                <input type="text" name="organization_name[]" class="form-control pds-input-borderless">
            </td>
            <td colspan="6"><input type="date" name="period_from[]" class="form-control pds-input-borderless"></td>
            <td colspan="6"><input type="date" name="period_to[]" class="form-control pds-input-borderless"></td>
            <td colspan="8"><input type="number" name="number_of_hours[]" class="form-control pds-input-borderless"></td>
            <td colspan="18"><input type="text" name="position[]" class="form-control pds-input-borderless"></td>
        </tr>`;
        table.insertAdjacentHTML('beforeend', newRow);
    }


    function removeMembershipRow() {
        let table = document.getElementById('membershipTable');
        if (table.rows.length > 0) {
            table.deleteRow(-1);
        }
    }
</script>
