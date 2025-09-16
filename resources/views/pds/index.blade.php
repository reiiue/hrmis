@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('css/pds.css') }}" rel="stylesheet"> 
<link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">


<div class="wrapper">


<!-- Main Content -->
@include('partials.sidebar')
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
