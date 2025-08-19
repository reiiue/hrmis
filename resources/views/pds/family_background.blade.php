<tr>
    <td colspan="30" class="pds-section-header">II. FAMILY BACKGROUND</td>
</tr>

{{-- 22. Spouse Information --}}
<tr>
    <td colspan="4" class="pds-label">22. SPOUSE'S SURNAME</td>
    <td colspan="10">
        <input type="text" name="spouse_last_name" 
            class="form-control pds-input-borderless"
            value="{{ old('spouse_last_name', $spouse->last_name ?? '') }}">
    </td>
</tr>

<tr>
    <td colspan="4" class="pds-label">FIRST NAME</td>
    <td colspan="5">
        <input type="text" name="spouse_first_name" 
            class="form-control pds-input-borderless"
            value="{{ old('spouse_first_name', $spouse->first_name ?? '') }}">
    </td>
    <td colspan="3" class="pds-label" style="font-size:10px">NAME EXTENSION</td>
    <td colspan="2">
        <input type="text" name="spouse_name_extension" 
            class="form-control pds-input-borderless mt-1"
            value="{{ old('spouse_name_extension', $spouse->name_extension ?? '') }}">
    </td>
</tr>

<tr>
    <td colspan="4" class="pds-label">MIDDLE NAME</td>
    <td colspan="10">
        <input type="text" name="spouse_middle_name" 
            class="form-control pds-input-borderless"
            value="{{ old('spouse_middle_name', $spouse->middle_name ?? '') }}">
    </td>
</tr>

<tr>
    <td colspan="4" class="pds-label">OCCUPATION</td>
    <td colspan="10">
        <input type="text" name="spouse_occupation" 
            class="form-control pds-input-borderless"
            value="{{ old('spouse_occupation', $spouse->occupation ?? '') }}">
    </td>
</tr>


<tr>
    <td colspan="4" class="pds-label">EMPLOYER/ BUSINESS NAME</td>
    <td colspan="10">
        <input type="text" name="employer_business_name" 
            class="form-control pds-input-borderless"
            value="{{ old('employer_business_name', $spouse->employer_business_name ?? '') }}">
    </td>
</tr>

<tr>
    <td colspan="4" class="pds-label">BUSINESS ADDRESS</td>
    <td colspan="10">
        <input type="text" name="business_address" 
            class="form-control pds-input-borderless"
            value="{{ old('business_address', $spouse->business_address ?? '') }}">
    </td>
</tr>

<tr>
    <td colspan="4" class="pds-label">TELEPHONE NO.</td>
    <td colspan="10">
        <input type="text" name="spouse_telephone_no" 
            class="form-control pds-input-borderless"
            value="{{ old('spouse_telephone_no', $spouse->telephone_no ?? '') }}">
    </td>
</tr>

