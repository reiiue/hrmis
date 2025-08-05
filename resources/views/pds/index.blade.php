@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('css/pds.css') }}" rel="stylesheet"> {{-- Custom CSS for long paper look --}}

<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center text-primary mb-4">HRMIS</h4>
        <a href="#">Home</a>
        <a href="#">Profile</a>
        <a href="{{ route('pds.index') }}" class="active">PDS</a>
        <a href="#">SALN</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link logout">
                Logout
            </button>
        </form>
    </div>

<!-- Main Content -->
<div class="pds-container bg-white shadow-sm p-4">

    <h4 class="mb-4 text-center">Personal Data Sheet (PDS)</h4>

    <form method="POST" action="{{ route('pds.update') }}">
        @csrf

        <table class="table pds-table">
            {{-- Header Row --}}
            <tr>
                <td colspan="4" class="pds-section-header">I. PERSONAL INFORMATION</td>
            </tr>

            {{-- 2. SURNAME --}}
            <tr>
                <td class="pds-label">2. SURNAME</td>
                <td colspan = "4">
                    <input type="text" name="last_name" class="pds-input-borderless"
                        value="{{ old('last_name', $personalInfo->last_name ?? '') }}">
                </td>
            </tr>

            {{-- FIRST NAME --}}
            <tr>
                <td class="pds-label">FIRST NAME</td>
                <td>
                    <input type="text" name="first_name" class="pds-input-borderless"
                        value="{{ old('first_name', $personalInfo->first_name ?? '') }}">
                </td>

                    <td class="pds-label">Name Extension (Jr, Sr)</td>
                <td colspan = "1">
                    <input type="text" name="suffix" class="pds-input-borderless"
                        value="{{ old('suffix', $personalInfo->suffix ?? '') }}">
                </td>
            </tr>

            {{-- MIDDLE NAME --}}
            <tr>
                <td class="pds-label">MIDDLE NAME</td>
                <td colspan="4">
                    <input type="text" name="middle_name" class="pds-input-borderless"
                        value="{{ old('middle_name', $personalInfo->middle_name ?? '') }}">
                </td>
            </tr>

            {{-- DATE OF BIRTH, CITIZENSHIP --}}
            <tr>
                <td class="pds-label">3. DATE OF BIRTH<br><small>(mm/dd/yyyy)</small></td>
                <td>
                    <input type="date" name="date_of_birth" class="pds-input-borderless"
                        value="{{ old('date_of_birth', optional($personalInfo)->date_of_birth ?: '') }}">
                </td>

                {{-- CITIZENSHIP LABEL AND OPTIONS --}}
                <td class="pds-label" rowspan="3" style="background: #e0e0e0; vertical-align: top;">16. CITIZENSHIP<br><br>
                    If holder of dual citizenship,<br>
                    please indicate the details.
                </td>
                <td rowspan="3">
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
                <td class="pds-label">4. PLACE OF BIRTH</td>
                <td>
                    <input type="text" name="place_of_birth" class="pds-input-borderless"
                         value="{{ old('place_of_birth', optional($personalInfo)->place_of_birth ?: '') }}">
                </td>
            </tr>

                <tr>
                    {{-- SEX --}}
                    <td class="pds-label">5. SEX</td>
                    <td>
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


            </table>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
    </form>
</div>


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
@endsection
