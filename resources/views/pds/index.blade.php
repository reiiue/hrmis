@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('css/pds.css') }}" rel="stylesheet"> 
<link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="wrapper">
    <!-- Sidebar -->
    @if (Auth::user()->role === 'Employee')
        @include('layouts.sidebar_employee')
    @elseif (Auth::user()->role === 'HR')
        @include('layouts.sidebar_hr')
    @elseif (Auth::user()->role === 'Admin')
        @include('layouts.sidebar_admin')
    @endif

    <div class="pds-container bg-white shadow-sm p-4">
        <h4 class="mb-4 text-center">Personal Data Sheet (PDS)</h4>

        <form method="POST" action="{{ route('pds.update') }}">
            @csrf

            <table class="pds-table section-table">
                @include('pds.personal_information')
            </table>

            <table class="pds-table section-table">
                @include('pds.family_background')
            </table>

            <table class="pds-table section-table">
                @include('pds.education')
            </table>

            <table class="pds-table section-table">
                @include('pds.civil_service_eligibility')
            </table>

            <table class="pds-table section-table">
                @include('pds.work_experience')
            </table>

            <table class="pds-table section-table">
                @include('pds.membership_associations')
            </table>

            <table class="pds-table section-table">
                @include('pds.learning_development')
            </table>

            <table class="pds-table section-table">
                @include('pds.special_skills_hobbies')
            </table>

            <table class="pds-table section-table">
                @include('pds.last_page')
            </table>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('pds.pds.pdf') }}" target="_blank" class="btn btn-success">Download PDF</a>

                <!-- Submit PDS Button -->
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#submitPDSModal">
                    Submit PDS
                </button>
            </div>
        </form>

        <!-- Modal for Submission -->
        <div class="modal fade" id="submitPDSModal" tabindex="-1" aria-labelledby="submitPDSModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('pds.submit') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="submitPDSModalLabel">Submit PDS</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Recipients -->
                            <div class="mb-3">
                                <label for="recipients" class="form-label">Select Recipients</label>
                                @php
                                // Fetch all HR and Admin users as possible recipients
                                $recipients = \App\Models\User::with('personalInformation')
                                    ->whereIn('role', ['HR', 'Admin'])
                                    ->get();
                                @endphp

                                <select name="recipients[]" id="recipients" class="form-select" multiple required>
                                    @foreach($recipients as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->personalInformation->first_name }} {{ $user->personalInformation->last_name }} ({{ $user->role }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple.</small>
                            </div>

                            <!-- Document Type -->
                            <div class="mb-3">
                                <label for="document_type" class="form-label">Document Type</label>
                                <select name="document_type" id="document_type" class="form-select" required>
                                    <option value="PDS" selected>PDS</option>
                                    <option value="SALN">SALN</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
