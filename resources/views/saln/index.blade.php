@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('css/saln.css') }}" rel="stylesheet"> 
<link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">

<div class="dashboard-container">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- SALN Main Content -->
    <main class="saln-main-content">
        <div class="saln-container">
            <h4 class="text-center mb-4" style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">
                SWORN STATEMENT OF ASSETS, LIABILITIES AND NET WORTH
            </h4>

            <form method="POST" action="{{ route('saln.update') }}">
                @csrf

                <table class="table personal-information-table">
                    @include('saln.personal_information')
                    @include('saln.children')
                </table>

                @include('saln.real_properties')
                @include('saln.personal_properties')
                @include('saln.liabilities')
                @include('saln.business_interests')
                @include('saln.relatives_in_gov_service')

                {{-- Save and Download Buttons --}}
            

                

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('saln.pdf') }}" target="_blank" class="btn btn-success">Download PDF</a>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection
