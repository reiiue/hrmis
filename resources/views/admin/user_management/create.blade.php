@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 fw-bold text-dark">Create New User</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-arrow-left-circle"></i> Back to Users
            </a>
        </div>
    </div>

    <!-- Card Form -->
    <div class="card custom-shadow border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="card-title mb-0 fw-semibold text-dark">
                <i class="bi bi-person-plus"></i> User Information
            </h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST" class="p-3">
                @csrf

                <!-- Username -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-muted">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control form-control-lg" 
                               placeholder="Enter username" required>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-muted">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control form-control-lg" 
                               placeholder="Enter password" required>
                    </div>
                </div>

                <!-- Role -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-muted">Role</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-person-badge"></i></span>
                        <select name="role" class="form-select form-select-lg" required>
                            <option value="" disabled selected>Select a role</option>
                            <option value="Employee">Employee</option>
                            <option value="HR">HR</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-muted">Status</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-activity"></i></span>
                        <select name="status" class="form-select form-select-lg" required>
                            <option value="Active" selected>Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Suspended">Suspended</option>
                            <option value="Pending">Pending</option>
                            <option value="Archived">Archived</option>
                        </select>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-check-circle"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Styles -->
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

.form-control-lg, .form-select-lg {
    font-size: 1.1rem;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
}

.input-group-text {
    border-radius: 0.5rem 0 0 0.5rem;
}

.btn-lg {
    font-size: 1.1rem;
    border-radius: 0.5rem;
}
</style>
@endsection
