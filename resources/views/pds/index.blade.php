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
    <div class="main-content">
        <div class="pds-paper bg-white p-5 shadow">
            <div class="text-center mb-4">
                <h2 class="fw-bold">PERSONAL DATA SHEET</h2>
                <p class="text-muted">Revised 2017 | Civil Service Commission</p>
            </div>

            {{-- Begin Form --}}
            <form action="{{ route('pds.update') }}" method="POST">
                @csrf
                @method('POST') {{-- Or PUT if updating --}}
                
                <section class="pds-boxed-section">
                    <div class="row no-gutters">
                        <div class="col-12 pds-header">
                            I. PERSONAL INFORMATION
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-2 pds-label">SURNAME</div>
                        <div class="col-10 p-0">
                            <input type="text" name="last_name" class="form-control pds-input" value="{{ old('last_name', $personalInfo->last_name ?? '') }}">
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-2 pds-label">FIRSTNAME</div>
                        <div class="col-6 p-0">
                            <input type="text" name="first_name" class="form-control pds-input-right" 
                                value="{{ old('first_name', $personalInfo->first_name ?? '') }}">
                        </div>
                        <div class="col-2 pds-label-small text-end">NAME EXTENSION (Jr, Sr)</div>
                        <div class="col-2 p-0">
                            <input type="text" name="suffix" class="form-control pds-input"
                                value="{{ old('suffix', $personalInfo->suffix ?? '') }}">
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-2 pds-label">MIDDLE NAME</div>
                        <div class="col-10 p-0">
                            <input type="text" name="middle_name" class="form-control pds-input" value="{{ old('middle_name', $personalInfo->middle_name ?? '') }}">
                        </div>
                    </div>

                    <div class="row no-gutters">
                        {{-- Date of Birth --}}
                        <div class="col-2 pds-label">Date Of Birth</div>
                        <div class="col-3 p-0">
                            <input type="date" name="date_of_birth" class="form-control pds-input" 
                                value="{{ old('date_of_birth', $personalInfo->date_of_birth ?? '') }}">
                        </div>
                    

                        {{-- Citizenship --}}
                        <div class="col-2 pds-label">Citizenship</div>
                        <div class="col-4 p-0">
                            <div class="pds-radio-wrapper d-flex align-items-center">
                                <div class="form-check form-check-inline mb-0 pds-radio-row">
                                    <input class="form-check-input" type="radio" name="citizenship" id="filipino" value="Filipino"
                                        {{ old('citizenship', $personalInfo->citizenship ?? '') == 'Filipino' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="filipino">Filipino</label>
                                </div>
                                <div class="form-check form-check-inline mb-0 pds-radio-row">
                                    <input class="form-check-input" type="radio" name="citizenship" id="dual" value="Dual Citizenship"
                                        {{ old('citizenship', $personalInfo->citizenship ?? '') == 'Dual Citizenship' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="dual">Dual Citizenship</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    

                    <div class="row no-gutters">
                        <div class="col-2 pds-label">Place of Birth</div>
                        <div class="col-3 p-0">
                            <input type="text" name="place_of_birth" class="form-control pds-input" 
                                value="{{ old('place_of_birth', $personalInfo->place_of_birth ?? '') }}">
                        </div>

                        {{-- Citizenship Radio Sub-options --}}
                        <div class="col-2 pds-label"></div>
                        <div class="col-4 p-0 by-options-wrapper">
                            <div class="pds-radio-wrapper d-flex align-items-center flex-wrap">
                                <div class="form-check form-check-inline mb-0 pds-radio-row by-options">
                                    <input class="form-check-input" type="radio" name="dual_citizenship_type" id="by_birth" value="By Birth"
                                        {{ old('dual_citizenship_type', $personalInfo->dual_citizenship_type ?? '') == 'By Birth' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="by_birth">by birth</label>
                                </div>
                                <div class="form-check form-check-inline mb-0 pds-radio-row by-options">
                                    <input class="form-check-input" type="radio" name="dual_citizenship_type" id="naturalization" value="By Naturalization"
                                        {{ old('dual_citizenship_type', $personalInfo->dual_citizenship_type ?? '') == 'By Naturalization' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="naturalization">by naturalization</label>
                                </div>
                            </div>
                        </div>                     
                    </div>


                    {{-- Sex --}}
                    <div class="row no-gutters">
                        <div class="col-2 pds-label">Sex</div>
                        <div class="col-3 p-0">
                            <div class="pds-radio-wrapper d-flex align-items-center">
                                <div class="form-check form-check-inline mb-0 pds-radio-row">
                                    <input class="form-check-input" type="radio" name="sex" id="male" value="Male"
                                        {{ old('sex', $personalInfo->sex ?? '') == 'Male' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="male">Male</label>
                                </div>
                                <div class="form-check form-check-inline mb-0 pds-radio-row">
                                    <input class="form-check-input" type="radio" name="sex" id="female" value="Female"
                                        {{ old('sex', $personalInfo->sex ?? '') == 'Female' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="female">Female</label>
                                </div>
                            </div>
                        </div>
                         <div class="col-2 pds-label"></div>
{{-- Country Dropdown Container: always visible with border --}}
<div class="col-5 p-0">
    <div class="country-dropdown-wrapper">
        <select name="dual_citizenship_country_id" id="dual-country-select"
            class="form-control country-select">
            <option value="">-- Select Country --</option>
            @foreach($countries as $country)
                <option value="{{ $country->id }}"
                    {{ old('dual_citizenship_country_id', $personalInfo->dual_citizenship_country_id ?? '') == $country->id ? 'selected' : '' }}>
                    {{ $country->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

                    </div>





                </section>

                    <button type="submit" class="btn btn-primary mt-3">Save</button>
                </form>

                    {{-- Add other fields later like date of birth, sex, etc. --}}
            </section>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dualCitizenshipRadio = document.getElementById('dual');
        const filipinoRadio = document.getElementById('filipino');
        const byOptions = document.querySelectorAll('.by-options');

        function toggleByOptions() {
            const isDual = dualCitizenshipRadio.checked;
            byOptions.forEach(option => {
                option.style.display = isDual ? 'inline-block' : 'none';
            });
        }

        // Initial check on page load
        toggleByOptions();

        // Listen for changes
        dualCitizenshipRadio.addEventListener('change', toggleByOptions);
        filipinoRadio.addEventListener('change', toggleByOptions);
    });

    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('dual-country-select');
        const dualOptions = document.querySelectorAll('input[name="dual_citizenship_type"]');

        function toggleSelect() {
            const selected = document.querySelector('input[name="dual_citizenship_type"]:checked');
            if (selected && selected.value === "By Naturalization") {
                select.style.display = 'block';
                select.disabled = false;
            } else {
                select.style.display = 'none';
                select.disabled = true;
            }
        }

        toggleSelect(); // run on load

        dualOptions.forEach(option => {
            option.addEventListener('change', toggleSelect);
        });
    });
</script>

@endsection
