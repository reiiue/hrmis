@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('css/pds.css') }}" rel="stylesheet"> 

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
                @include('pds.last_page')
            </table>


            
        <div class="text-end mt-3">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('pdf.download') }}" target="_blank" class="btn btn-success">Download PDF</a>
        </div>

    </form>
</div>



@endsection
