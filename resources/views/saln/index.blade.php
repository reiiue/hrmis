{{-- resources/views/saln/index.blade.php --}}

@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('css/saln.css') }}" rel="stylesheet"> 

<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center text-primary mb-4">HRMIS</h4>
        <a href="#">Home</a>
        <a href="#">Profile</a>
        <a href="{{ route('pds.index') }}">PDS</a>
        <a href="{{ route('saln.index') }}" class="active">SALN</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link logout">
                Logout
            </button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="saln-container bg-white shadow-sm">

        <h4 class="text-center" style="font-family: 'Times New Roman', Times, serif; font-weight: Bold; ">SWORN STATEMENT OF ASSETS, LIABILITIES AND NET WORTH</h4>

        <form method="POST" action="{{ route('saln.update') }}">
            @csrf

        <table class="table saln-table">
            @include('saln.personal_information')
            @include('saln.children')
            

        </table>

                
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('saln.pdf') }}" target="_blank" class="btn btn-success">Download PDF</a>
            </div>
        </form>
    </div>
</div>
@endsection
