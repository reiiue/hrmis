<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <!-- Header -->
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title fw-bold" id="editUserModalLabel">
          <i class="bi bi-pencil-square"></i> Edit User
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Form -->
      <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="row g-3">
            <!-- Employee Info -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Employee ID</label>
              <input type="text" name="agency_employee_no" class="form-control" value="{{ old('agency_employee_no', $user->agency_employee_no) }}" required>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Department</label>
              <input type="text" name="department" class="form-control" value="{{ old('department', $user->department) }}" required>
            </div>

            <!-- Name Fields -->
            <div class="col-md-4">
              <label class="form-label fw-semibold">Last Name</label>
              <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}" required>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">First Name</label>
              <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}" required>
            </div>

            <div class="col-md-2">
              <label class="form-label fw-semibold">M.I.</label>
              <input type="text" name="middle_name" maxlength="1" class="form-control text-uppercase" value="{{ old('middle_name', $user->middle_name) }}" required>
            </div>

            <div class="col-md-2">
              <label class="form-label fw-semibold">Ext.</label>
              <input type="text" name="suffix" class="form-control" value="{{ old('suffix', $user->suffix) }}">
            </div>

            <!-- Account Info -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Email</label>
              <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Password</label>
              <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Confirm Password</label>
              <input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter password">
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Role</label>
              <select name="role" class="form-select" required>
                <option value="Admin" {{ old('role', $user->role) == 'Admin' ? 'selected' : '' }}>Admin</option>
                <option value="HR" {{ old('role', $user->role) == 'HR' ? 'selected' : '' }}>HR</option>
                <option value="Employee" {{ old('role', $user->role) == 'Employee' ? 'selected' : '' }}>Employee</option>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Status</label>
              <select name="status" class="form-select" required>
                <option value="Active" {{ old('status', $user->status) == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ old('status', $user->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="Suspended" {{ old('status', $user->status) == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="Pending" {{ old('status', $user->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Archived" {{ old('status', $user->status) == 'Archived' ? 'selected' : '' }}>Archived</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Cancel
          </button>
          <button type="submit" class="btn btn-warning text-white">
            <i class="bi bi-pencil-square"></i> Update User
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editUserModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        // Populate form fields
        editModal.querySelector('form').action = `/admin/users/${button.dataset.id}`;
        editModal.querySelector('input[name="first_name"]').value = button.dataset.first_name;
        editModal.querySelector('input[name="last_name"]').value = button.dataset.last_name;
        editModal.querySelector('input[name="middle_name"]').value = button.dataset.middle_name;
        editModal.querySelector('input[name="suffix"]').value = button.dataset.suffix;
        editModal.querySelector('input[name="email"]').value = button.dataset.email;
        editModal.querySelector('input[name="department"]').value = button.dataset.department;
        editModal.querySelector('input[name="agency_employee_no"]').value = button.dataset.agency_employee_no;
        editModal.querySelector('select[name="role"]').value = button.dataset.role;
        editModal.querySelector('select[name="status"]').value = button.dataset.status;
    });
});
</script>
