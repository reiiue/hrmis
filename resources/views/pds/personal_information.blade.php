{{-- Header Row --}}
<tr>
    <td colspan="62" class="pds-section-header">I. PERSONAL INFORMATION</td>
</tr>

{{-- 2. SURNAME --}}
<tr>
    <td colspan="7" class="pds-label">2. SURNAME</td>
    <td colspan = "55">
        <input type="text" name="last_name" class="form-control pds-input-borderless"
        value="{{ old('last_name', $personalInfo->last_name ?? '') }}">
    </td>
</tr>

{{-- FIRST NAME --}}
<tr>
    <td colspan="7" class="pds-label">FIRST NAME</td>
    <td colspan = "42">
        <input type="text" name="first_name" class="form-control pds-input-borderless"
        value="{{ old('first_name', $personalInfo->first_name ?? '') }}">
    </td>

    <td colspan="8" class="pds-label">Name Extension (Jr, Sr)</td>
    <td colspan = "5">
    <input type="text" name="suffix" class="form-control pds-input-borderless"
        value="{{ old('suffix', $personalInfo->suffix ?? '') }}">
    </td>
</tr>

{{-- MIDDLE NAME --}}
<tr>
    <td colspan="7" class="pds-label">MIDDLE NAME</td>
    <td colspan="55">
        <input type="text" name="middle_name" class="form-control pds-input-borderless"
        value="{{ old('middle_name', $personalInfo->middle_name ?? '') }}">
    </td>
</tr>

{{-- DATE OF BIRTH --}}
<tr>
    <td colspan="7" class="pds-label">3. DATE OF BIRTH<br><small>(mm/dd/yyyy)</small></td>
    <td colspan="23">
        <input type="date" name="date_of_birth" class="form-control pds-input-borderless"
        value="{{ old('date_of_birth', optional($personalInfo)->date_of_birth ?: '') }}">
    </td>

{{-- CITIZENSHIP --}}
<td class="pds-label" colspan="12" rowspan="3" style="vertical-align: top;">16. CITIZENSHIP<br><br>
    If holder of dual citizenship,<br>
    please indicate the details.
</td>
<td colspan="20" rowspan="3">
<div class="d-flex align-items-center gap-3 mb-2">
{{-- Filipino Option --}}
    <div class="form-check">
    <input class="form-check-input" type="radio" name="citizenship" id="filipino" value="Filipino"
    {{ old('citizenship', optional($personalInfo)->citizenship) == 'Filipino' ? 'checked' : '' }}
        onclick="toggleDualOptions()">
    <label class="form-check-label" for="filipino">Filipino</label>
</div>

{{-- Dual Citizenship Option --}}
<div class="form-check">
    <input class="form-check-input" type="radio" name="citizenship" id="dual" value="Dual Citizenship"
        {{ old('citizenship', optional($personalInfo)->citizenship) == 'Dual Citizenship' ? 'checked' : '' }}
            onclick="toggleDualOptions()">
        <label class="form-check-label" for="dual">Dual Citizenship</label>
    </div>
</div>

{{-- If Dual Citizenship: by birth / by naturalization --}}
<div id="dual-options" class="d-flex align-items-center gap-3 mb-2">
    <div class="form-check">
        <input class="form-check-input" type="radio" name="dual_citizenship_type" id="by_birth" value="By Birth"
            {{ old('dual_citizenship_type', optional($personalInfo)->dual_citizenship_type) == 'By Birth' ? 'checked' : '' }}>
            <label class="form-check-label" for="by_birth">by birth</label>
        </div>
<div class="form-check">
    <input class="form-check-input" type="radio" name="dual_citizenship_type" id="by_naturalization" value="By Naturalization"
        {{ old('dual_citizenship_type', optional($personalInfo)->dual_citizenship_type) == 'By Naturalization' ? 'checked' : '' }}>
        <label class="form-check-label" for="by_naturalization">by naturalization</label>
    </div>
</div>

{{-- Country Dropdown --}}
<div id="country-container" class="mb-2">
    <label for="dual_citizenship_country_id" class="form-label">Pls. indicate country:</label>
            <select name="dual_citizenship_country_id" id="dual_citizenship_country_id" class="form-select small-dropdown">
                    <option value="">Select Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}"
                                {{ old('dual_citizenship_country_id', optional($personalInfo)->dual_citizenship_country_id) == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </td>
    </tr>

    <tr>
    {{-- PLACE OF BIRTH --}}
    <td colspan="7" class="pds-label">4. PLACE OF BIRTH</td>
    <td colspan="23">
        <input type="text" name="place_of_birth" class="form-control pds-input-borderless"
            value="{{ old('place_of_birth', optional($personalInfo)->place_of_birth ?: '') }}">
        </td>
    </tr>

    <tr>
    {{-- SEX --}}
    <td colspan="7 "class="pds-label">5. SEX</td>
    <td colspan="23">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="sex" id="male" value="Male"
                {{ old('sex', optional($personalInfo)->sex) == 'Male' ? 'checked' : '' }}>
            <label class="form-check-label" for="male">Male</label>
        </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="sex" id="female" value="Female"
            {{ old('sex', optional($personalInfo)->sex) == 'Female' ? 'checked' : '' }}>
        <label class="form-check-label" for="female">Female</label>
</div>
                
</td>
            </tr>



                <tr>
                    <!-- Civil Status -->
                    <td colspan="7" class="pds-label"> 6. CIVIL STATUS</td>
                    <td colspan="23">
                        @foreach (['Single', 'Married', 'Widowed', 'Separated', 'Others'] as $status)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="civil_status" value="{{ $status }}"
                                    {{ old('civil_status', $personalInfo->civil_status ?? '') == $status ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $status }}</label>
                            </div>
                        @endforeach
                    </td>

                    <!-- Residential Address Label + ZIP Code -->
                    <td colspan="10" class="pds-label align-top" rowspan="3">
                        <strong>17. RESIDENTIAL ADDRESS</strong>
                        <br><br>
                        <input type="text" name="address[residential][zip_code]"
                            class="form-control address-input mt-2"
                            value="{{ old('address.residential.zip_code', $residentialAddress->zip_code ?? '') }}">
                        <div class="address-label"><em>ZIP CODE</em></div>
                    </td>

                    <!-- House/Block/Lot No. / Street -->
                    <td colspan="11">
                        <input type="text" name="address[residential][house_block_lot_no]" 
                                class="address-input"
                                value="{{ old('address.residential.house_block_lot_no', $residentialAddress->house_block_lot_no ?? '') }}">
                            <div class="address-label"><em>House/Block/Lot No.</em></div>
                    </td>
                        
                    <td colspan="11">
                        <input type="text" name="address[residential][street]" 
                                class="address-input"
                                value="{{ old('address.residential.street', $residentialAddress->street ?? '') }}">
                            <div class="address-label"><em>Street</em></div>
                    </td>
                </tr>

                <tr>
                    <!-- Height -->
                    <td colspan="7" class="pds-label">7. HEIGHT (m)</td>
                    <td colspan="23">
                        <input type="text" name="height" class="form-control pds-input-borderless"
                            value="{{ old('height', $personalInfo->height ?? '') }}">
                    </td>

                    <!-- Subdivision / Barangay -->
                    <td colspan="11">
                        <input type="text" name="address[residential][subdivision]" 
                            class="form-control address-input"
                            value="{{ old('address.residential.subdivision', $residentialAddress->subdivision ?? '') }}">
                        <div class="address-label"><em>Subdivision/Village</em></div>
                    </td>
                    <td colspan="11">
                        <input type="text" name="address[residential][barangay]" 
                            class="form-control address-input"
                            value="{{ old('address.residential.barangay', $residentialAddress->barangay ?? '') }}">
                        <div class="address-label"><em>Barangay</em></div>
                    </td>
                </tr>

                <tr>
                    <!-- Weight -->
                    <td colspan="7" class="pds-label">8. WEIGHT (kg)</td>
                    <td colspan="23">
                        <input type="text" name="weight" class="form-control pds-input-borderless""
                            value="{{ old('weight', $personalInfo->weight ?? '') }}">
                    </td>

                    <!-- City / Province -->
                    <td colspan="11">
                        <input type="text" name="address[residential][city]" 
                            class="form-control address-input"
                            value="{{ old('address.residential.city', $residentialAddress->city ?? '') }}">
                        <div class="address-label"><em>City/Municipality</em></div>
                    </td>
                    <td colspan="11">
                        <input type="text" name="address[residential][province]" 
                            class="form-control address-input"
                            value="{{ old('address.residential.province', $residentialAddress->province ?? '') }}">
                        <div class="address-label"><em>Province</em></div>
                    </td>
                </tr>


                <tr>

                    <!-- Blood Type -->
                    <td colspan="7" class="pds-label">9. BLOOD TYPE</td>
                    <td colspan="23">
                        <select name="blood_type" class="form-select pds-input-borderless">
                            <option value="">-- Select --</option>
                            @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $type)
                                <option value="{{ $type }}" 
                                    {{ old('blood_type', $personalInfo->blood_type ?? '') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </td>

                    <!-- Permanent Address Label + ZIP Code -->
                    <td class="pds-label align-top" colspan="10" rowspan="4">18. PERMANENT ADDRESS
                        <br><br><br><br><br><br><br><br>
                        <div class="address-label"><em>ZIP CODE</em></div>
                    </td>

                    <!-- House/Block/Lot No. / Street -->
                    <td colspan="11">
                        <input type="text" name="address[permanent][house_block_lot_no]" 
                            class="address-input"
                            value="{{ old('address.permanent.house_block_lot_no', $permanentAddress->house_block_lot_no ?? '') }}">
                            <div class="address-label"><em>House/Block/Lot No.</em></div>
                    </td>
                        
                    <td colspan="11">
                        <input type="text" name="address[permanent][street]" 
                            class="address-input"
                            value="{{ old('address.permanent.street', $permanentAddress->street ?? '') }}">
                        <div class="address-label"><em>Street</em></div>
                    </td>
                </tr>

                <tr>
                    <!-- GSIS ID NO. -->
                    <td colspan="7" class="pds-label">10. GSIS ID NO.</td>
                    <td colspan="23">
                        <input type="text" name="gsis_id" 
                            class="form-control pds-input-borderless"
                            value="{{ old('gsis_id', $governmentIds->gsis_id ?? '') }}">
                    </td>

                    <!-- Subdivision / Barangay -->
                    <td colspan="11">
                        <input type="text" name="address[permanent][subdivision]" 
                            class="form-control address-input"
                            value="{{ old('address.permanent.subdivision', $permanentAddress->subdivision ?? '') }}">
                        <div class="address-label"><em>Subdivision/Village</em></div>
                    </td>
                    <td colspan="11">
                        <input type="text" name="address[permanent][barangay]" 
                            class="form-control address-input"
                            value="{{ old('address.permanent.barangay', $permanentAddress->barangay ?? '') }}">
                        <div class="address-label"><em>Barangay</em></div>
                    </td>
                </tr>

                <tr>
                    <!-- PAG-IBIG ID NO. -->
                    <td colspan="7" class="pds-label"><strong>11. PAG-IBIG ID NO.</strong></td>
                    <td colspan="23">
                        <input type="text" name="pagibig_id" 
                            class="form-control pds-input-borderless"
                            value="{{ old('pagibig_id', $governmentIds->pagibig_id ?? '') }}">
                    </td>

                    <!-- City / Province -->
                    <td colspan="11">
                        <input type="text" name="address[permanent][city]" 
                            class="form-control address-input"
                            value="{{ old('address.permanent.city', $permanentAddress->city ?? '') }}">
                        <div class="address-label"><em>City/Municipality</em></div>
                    </td>
                    <td colspan="11">
                        <input type="text" name="address[permanent][province]" 
                            class="form-control address-input"
                            value="{{ old('address.permanent.province', $permanentAddress->province ?? '') }}">
                        <div class="address-label"><em>Province</em></div>
                    </td>
                </tr>

                <tr>
                    <!-- PHILHEALTH NO. -->
                    <td colspan="7" class="pds-label">12. PHILHEALTH NO.</td>
                    <td colspan="23">
                        <input type="text" name="philhealth_id" 
                            class="form-control pds-input-borderless"
                            value="{{ old('philhealth_id', $governmentIds->philhealth_id ?? '') }}">
                    </td>

                    <!-- ZIP CODE -->
                    <td colspan="22">
                        <input type="text" name="address[permanent][zip_code]"
                            class="form-control address-input"
                            value="{{ old('address.permanent.zip_code', $permanentAddress->zip_code ?? '') }}">
                    </td>

                </tr>

                <tr>
                    <!-- SSS NO. -->
                    <td colspan="7"class="pds-label">13. SSS NO.</td>
                    <td colspan="23">
                        <input type="text" name="sss_id" 
                            class="form-control pds-input-borderless"
                            value="{{ old('sss_id', $governmentIds->sss_id ?? '') }}">
                    </td>

                    <!-- TELEPHONE NO. -->
                    <td colspan="10" class="pds-label">19. TELEPHONE NO.</td>
                    <td colspan="22">
                        <input type="text" name="telephone_no" 
                            class="form-control pds-input-borderless"
                            value="{{ old('telephone_no', $personalInfo->telephone_no ?? '') }}">
                    </td>
                </tr>

                <tr>
                    <!--TIN NO. -->
                    <td colspan="7" class="pds-label">14. TIN NO.</td>
                    <td colspan="23">
                        <input type="text" name="tin_id" 
                            class="form-control pds-input-borderless"
                            value="{{ old('tin_id', $governmentIds->tin_id ?? '') }}">
                    </td>

                    <!-- MOBILE NO. -->
                    <td colspan="10"class="pds-label">20. MOBILE NO.</td>
                    <td colspan="22">
                        <input type="text" name="mobile_no" 
                            class="form-control pds-input-borderless"
                            value="{{ old('mobile_no', $personalInfo->mobile_no ?? '') }}">
                    </td>
                </tr> 

                <tr>
                    <!-- Agency Employee No. -->
                    <td colspan="7" class="pds-label">15. AGENCY EMPLOYEE NO.</td>
                    <td colspan="23">
                        <input type="text" name="agency_employee_no" 
                            class="form-control pds-input-borderless"
                            value="{{ old('agency_employee_no', $personalInfo->agency_employee_no ?? '') }}">
                    </td>

                    <!-- Email -->
                    <td colspan="10" class="pds-label">15. EMAIL ADDRESS.</td>
                    <td colspan="22">
                        <input type="text" name="email" 
                            class="form-control pds-input-borderless"
                            value="{{ old('email', $personalInfo->email ?? '') }}">
                    </td>
                </tr>


<script>
    function toggleDualOptions() {
        const isDual = document.getElementById('dual').checked;

        // Dual type radio buttons
        const dualOptions = document.querySelectorAll('#dual-options input');
        dualOptions.forEach(option => {
            option.disabled = !isDual;
            if (!isDual) option.checked = false;
        });

        // Country dropdown
        const countrySelect = document.getElementById('dual_citizenship_country_id');
        countrySelect.disabled = !isDual;
        if (!isDual) {
            countrySelect.selectedIndex = 0; // reset to placeholder
        }
    }

    // Run on page load
    window.onload = toggleDualOptions;
</script>