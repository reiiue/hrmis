<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <!-- Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold" id="createUserModalLabel">
          <i class="bi bi-person-plus"></i> Create New User
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Form -->
      <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="row g-3">
            <!-- Employee Info -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Employee ID</label>
              <input type="text" name="agency_employee_no" class="form-control" placeholder="Enter employee ID" required>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Department</label>
              <input type="text" name="department" class="form-control" placeholder="Enter department" required>
            </div>

            <!-- Name Fields -->
            <div class="col-md-4">
              <label class="form-label fw-semibold">Last Name</label>
              <input type="text" name="last_name" class="form-control" placeholder="Enter last name" required>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">First Name</label>
              <input type="text" name="first_name" class="form-control" placeholder="Enter first name" required>
            </div>

            <div class="col-md-2">
              <label class="form-label fw-semibold">M.I.</label>
              <input type="text" name="middle_name" maxlength="1" class="form-control text-uppercase" placeholder="M" required>
            </div>

            <div class="col-md-2">
              <label class="form-label fw-semibold">Ext.</label>
              <input type="text" name="suffix" class="form-control" placeholder="Jr., Sr., etc.">
            </div>

            <!-- Account Info -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Email</label>
              <input type="email" name="email" class="form-control" placeholder="Enter email" required>
            </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter password" required>
          </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Role</label>
              <select name="role" class="form-select" required>
                <option value="">Select Role</option>
                <option value="Admin">Admin</option>
                <option value="HR">HR</option>
                <option value="Employee">Employee</option>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Status</label>
              <select name="status" class="form-select" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
                <option value="Suspended">Suspended</option>
                <option value="Pending">Pending</option>
                <option value="Archived">Archived</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Cancel
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Save User
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
