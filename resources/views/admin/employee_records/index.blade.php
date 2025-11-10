@extends('layouts.admin')

@section('title', 'Employee Records')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 fw-bold text-dark">Employee Records</h1>
        </div>
        <div class="col-md-6 d-flex justify-content-end align-items-center gap-2">
            <!-- Search input -->
            <form action="{{ route('admin.employee.records') }}" method="GET" class="d-flex align-items-center gap-2">

                <!-- Smaller Search Bar -->
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="form-control" placeholder="Search..." style="width: 280px;">

                <!-- Filter dropdown button -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-funnel"></i> Filters
                    </button>
                    <div class="dropdown-menu p-3" aria-labelledby="filterDropdown" style="min-width: 220px;">
                        <strong>PDS</strong>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="pds_status[]" value="filed" id="pds_filed"
                                {{ is_array(request('pds_status')) && in_array('filed', request('pds_status')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="pds_filed">Filed</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="pds_status[]" value="not_filed" id="pds_not_filed"
                                {{ is_array(request('pds_status')) && in_array('not_filed', request('pds_status')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="pds_not_filed">Not Filed</label>
                        </div>

                        <hr class="dropdown-divider">

                        <strong>SALN</strong>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="saln_status[]" value="filed" id="saln_filed"
                                {{ is_array(request('saln_status')) && in_array('filed', request('saln_status')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="saln_filed">Filed</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="saln_status[]" value="not_filed" id="saln_not_filed"
                                {{ is_array(request('saln_status')) && in_array('not_filed', request('saln_status')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="saln_not_filed">Not Filed</label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm mt-2 w-100">Apply</button>
                    </div>
                </div>
            </form>
        </div>


    </div>

    <!-- Alert -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Employees Table -->
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
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>Position</th>
                                <th>PDS Status</th>
                                <th>SALN Status</th>
                                <th>Last Updated</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $index => $employee)
                                <tr class="fs-5">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $employee->personalInformation->first_name ?? '' }} {{ $employee->personalInformation->last_name ?? '' }}</td>
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
                                        @endphp
                                        <span class="badge {{ $pdsColors[$pdsStatus] ?? 'bg-secondary' }}">
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
                                        @endphp
                                        <span class="badge {{ $salnColors[$salnStatus] ?? 'bg-secondary' }}">
                                            {{ ucfirst(str_replace('_', ' ', $salnStatus)) }}
                                        </span>
                                    </td>

                                    <td>{{ $employee->pds ? $employee->pds->updated_at->format('Y-m-d') : 'N/A' }}</td>
                                    <!-- Actions -->
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-sm btn-outline-primary view-pdf-btn" 
                                                    data-id="{{ $employee->id }}" data-type="pds"
                                                    {{ $employee->pds ? '' : 'disabled title=Not yet filed' }}>
                                                <i class="bi bi-file-earmark-text me-1"></i> PDS
                                            </button>

                                            <button class="btn btn-sm btn-outline-warning view-pdf-btn" 
                                                    data-id="{{ $employee->id }}" data-type="saln"
                                                    {{ $employee->saln ? '' : 'disabled title=Not yet filed' }}>
                                                <i class="bi bi-file-earmark-text-fill me-1"></i> SALN
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($employees->hasPages())
                    <div class="card-footer bg-white border-top">
                        {{ $employees->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <h5 class="mt-3 text-muted">No employees found</h5>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Reusable Modal for PDF Review (Auto-fit) -->
<div class="modal fade" id="pdfModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" id="pdfModalHeader">
                <h5 class="modal-title" id="pdfModalTitle">PDF Viewer</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="height:80vh; display:flex; justify-content:center; align-items:center;">
                <embed id="pdfEmbed" src="" type="application/pdf" style="width:100%; height:100%;" />
            </div>
            <div class="modal-footer">
                <form id="pdfActionForm" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success me-2" name="action" value="approve">
                        <i class="bi bi-check-circle me-1"></i> Approve
                    </button>
                    <button type="submit" class="btn btn-danger" name="action" value="reject">
                        <i class="bi bi-x-circle me-1"></i> Reject
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.custom-shadow {
    box-shadow: 0 4px 20px rgba(0,0,0,0.08), 0 2px 8px rgba(0,0,0,0.06);
    transition: 0.3s ease;
    border-radius: 0.75rem;
}
.custom-shadow:hover { transform: translateY(-3px); }
.table td { font-size:1.1rem; padding:1rem !important; }
.badge { font-size:1rem; border-radius:0.5rem; }
.btn { font-size:1rem; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const pdfModal = new bootstrap.Modal(document.getElementById('pdfModal'));
    const pdfEmbed = document.getElementById('pdfEmbed');
    const pdfTitle = document.getElementById('pdfModalTitle');
    const pdfHeader = document.getElementById('pdfModalHeader');
    const pdfActionForm = document.getElementById('pdfActionForm');

    const routes = {
        pdsAction: "{{ route('admin.employee.records.pds-action', ['id' => '__ID__']) }}",
        salnAction: "{{ route('admin.employee.records.saln-action', ['id' => '__ID__']) }}"
    };

    document.querySelectorAll('.view-pdf-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const type = this.dataset.type;

            pdfTitle.textContent = type.toUpperCase() + ' PDF';
            pdfHeader.className = 'modal-header ' + (type === 'pds' ? 'bg-primary text-white' : 'bg-warning text-dark');

            pdfEmbed.src = type === 'pds' 
                ? `/admin/employee/pds/${id}` 
                : `/admin/employee/saln/${id}`;

            pdfActionForm.action = type === 'pds' 
                ? routes.pdsAction.replace('__ID__', id)
                : routes.salnAction.replace('__ID__', id);

            pdfModal.show();
        });
    });
});
</script>
@endsection
