@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 fw-bold text-dark">User Management</h1>
        </div>
        <div class="col-md-6 text-end d-flex justify-content-end gap-2">
            <!-- Search Form -->
            <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex" style="max-width: 350px;">
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="form-control me-2" placeholder="Search users...">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            <!-- Create Button -->
            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="bi bi-plus-circle"></i> Create User
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Users Table Card -->
    <div class="card custom-shadow border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="card-title mb-0 fw-semibold">All Users</h5>
        </div>
        <div class="card-body p-0">
            @if ($users->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-bold text-muted">Name</th>
                                <th class="fw-bold text-muted">Role</th>
                                <th class="fw-bold text-muted">Status</th>
                                <th class="fw-bold text-muted text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="fs-5">
                                    <!-- Full Name -->
                                    <td>
                                        <span class="text-dark">
                                            {{ $user->personalInformation ? 
                                                ucwords($user->personalInformation->first_name . ' ' . $user->personalInformation->last_name) : 'N/A' }}
                                        </span>
                                    </td>

                                    <!-- Role Badge -->
                                    <td>
                                        @switch($user->role)
                                            @case('Admin')
                                                <span class="badge bg-danger"><i class="bi bi-shield-lock"></i> Admin</span>
                                                @break
                                            @case('HR')
                                                <span class="badge bg-warning text-dark"><i class="bi bi-people"></i> HR</span>
                                                @break
                                            @case('Employee')
                                                <span class="badge bg-secondary"><i class="bi bi-person"></i> Employee</span>
                                                @break
                                            @default
                                                <span class="badge bg-light text-dark"><i class="bi bi-question-circle"></i> Unknown</span>
                                        @endswitch
                                    </td>

                                    <!-- Status Badge -->
                                    <td>
                                        @switch($user->status)
                                            @case('Active')
                                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Active</span>
                                                @break
                                            @case('Inactive')
                                                <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Inactive</span>
                                                @break
                                            @case('Suspended')
                                                <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle"></i> Suspended</span>
                                                @break
                                            @case('Pending')
                                                <span class="badge bg-info text-dark"><i class="bi bi-hourglass-split"></i> Pending</span>
                                                @break
                                            @case('Archived')
                                                <span class="badge bg-dark"><i class="bi bi-archive"></i> Archived</span>
                                                @break
                                            @default
                                                <span class="badge bg-light text-dark"><i class="bi bi-question-circle"></i> Unknown</span>
                                        @endswitch
                                    </td>

                                    <!-- Actions -->
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- Edit Button -->
                                            <button type="button" class="btn btn-sm btn-outline-primary edit-btn" 
                                                    data-bs-toggle="modal" data-bs-target="#editUserModal"
                                                    data-id="{{ $user->id }}"
                                                    data-first_name="{{ $user->personalInformation->first_name ?? '' }}"
                                                    data-last_name="{{ $user->personalInformation->last_name ?? '' }}"
                                                    data-middle_name="{{ $user->personalInformation->middle_name ?? '' }}"
                                                    data-suffix="{{ $user->personalInformation->suffix ?? '' }}"
                                                    data-email="{{ $user->email }}"
                                                    data-role="{{ $user->role }}"
                                                    data-status="{{ $user->status }}"
                                                    data-department="{{ $user->personalInformation->department ?? '' }}"
                                                    data-agency_employee_no="{{ $user->personalInformation->agency_employee_no ?? '' }}"
                                                    title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                                  method="POST" 
                                                  style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Are you sure?')"
                                                        title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($users->hasPages())
                    <div class="card-footer bg-white border-top">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <h5 class="mt-3 text-muted">No users found</h5>
                    <p class="text-muted mb-3">Get started by creating your first user</p>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Create User
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Box shadow enhancements */
.custom-shadow {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.06);
    transition: box-shadow 0.3s ease, transform 0.2s ease;
    border-radius: 0.75rem;
}

/* Subtle hover lift for interactivity */
.custom-shadow:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12), 0 4px 10px rgba(0, 0, 0, 0.08);
    transform: translateY(-3px);
}

/* Table spacing and text size */
.table th, .table td {
    padding: 1rem !important;
}
.table td {
    font-size: 1.1rem;
}

/* Badges and buttons */
.badge {
    font-size: 1rem !important;
    border-radius: 0.5rem !important;
}
.btn {
    font-size: 1rem !important;
}
</style>
@include('admin.partials.create_modal')
@include('admin.partials.edit_modal')

@endsection

