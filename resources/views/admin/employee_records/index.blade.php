@extends('layouts.admin')

@section('title', 'Employee Records')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 fw-bold text-dark">Employee Records</h1>
        </div>
        <div class="col-md-6 text-end d-flex justify-content-end gap-2">
            <!-- Search Form -->
            <form action="{{ route('admin.employee.records') }}" method="GET" class="d-flex" style="max-width: 350px;">
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="form-control me-2" placeholder="Search by name or position...">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Alert Messages -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Employees Table Card -->
    <div class="card custom-shadow border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="card-title mb-0 fw-semibold">All Employees</h5>
        </div>
        <div class="card-body p-0">
            @if ($employees->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-bold text-muted">#</th>
                                <th class="fw-bold text-muted">Employee Name</th>
                                <th class="fw-bold text-muted">Position</th>
                                <th class="fw-bold text-muted">PDS Status</th>
                                <th class="fw-bold text-muted">SALN Status</th>
                                <th class="fw-bold text-muted">Last Updated</th>
                                <th class="fw-bold text-muted text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $index => $employee)
                                <tr class="fs-5">
                                    <!-- Name -->
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $employee->personalInformation->first_name ?? '' }} 
                                        {{ $employee->personalInformation->last_name ?? '' }}
                                    </td>

                                    <!-- Position -->
                                    <td>{{ $employee->personalInformation->position ?? '' }}</td>

                                    <!-- PDS Status -->
                                    <td>
                                        @php
                                            $pdsStatus = $employee->pds->status ?? 'not_started';
                                            $pdsColors = [
                                                'in_progress' => 'bg-info text-dark',
                                                'not_started' => 'bg-secondary',
                                                'submitted'   => 'bg-warning text-dark',
                                                'approved'    => 'bg-success',
                                                'rejected'    => 'bg-danger',
                                            ];
                                            $pdsColor = $pdsColors[$pdsStatus] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $pdsColor }}">
                                            {{ ucfirst(str_replace('_', ' ', $pdsStatus)) }}
                                        </span>
                                    </td>

                                    <!-- SALN Status -->
                                    <td>
                                        @php
                                            $salnStatus = $employee->saln->status ?? 'not_filed';
                                            $salnColors = [
                                                'not_filed' => 'bg-secondary',
                                                'submitted' => 'bg-warning text-dark',
                                                'verified'  => 'bg-success',
                                                'flagged'   => 'bg-danger',
                                            ];
                                            $salnColor = $salnColors[$salnStatus] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $salnColor }}">
                                            {{ ucfirst(str_replace('_', ' ', $salnStatus)) }}
                                        </span>
                                    </td>

                                    <!-- Last Updated (PDS) -->
                                    <td>
                                        {{ $employee->pds ? $employee->pds->updated_at->format('Y-m-d') : 'N/A' }}
                                    </td>

                                    <!-- Actions -->
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.employee.pds.show', $employee->id) }}" 
                                            target="_blank"
                                            class="btn btn-sm btn-outline-primary" 
                                            title="View PDS">
                                                <i class="bi bi-file-earmark-text me-1"></i> View PDS
                                            </a>
                                            <a href="{{ route('admin.employee.saln.show', $employee->id) }}" 
                                            target="_blank"
                                            class="btn btn-sm btn-outline-warning" 
                                            title="View SALN">
                                                <i class="bi bi-file-earmark-text-fill me-1"></i> View SALN
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($employees->hasPages())
                    <div class="card-footer bg-white border-top">
                        {{ $employees->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <h5 class="mt-3 text-muted">No employees found</h5>
                    <p class="text-muted mb-3">Start by adding employee records</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.custom-shadow {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.06);
    transition: box-shadow 0.3s ease, transform 0.2s ease;
    border-radius: 0.75rem;
}
.custom-shadow:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12), 0 4px 10px rgba(0, 0, 0, 0.08);
    transform: translateY(-3px);
}
.table th, .table td {
    padding: 1rem !important;
}
.table td {
    font-size: 1.1rem;
}
.badge {
    font-size: 1rem !important;
    border-radius: 0.5rem !important;
}
.btn {
    font-size: 1rem !important;
}
</style>
@endsection
