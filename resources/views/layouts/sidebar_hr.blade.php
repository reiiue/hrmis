<aside class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <div class="logo-icon">
                <img src="{{ asset('images/bipsu_logo.png') }}" alt="eHRMIS Logo" class="logo-img">
            </div>
            <span class="logo-text">eHRMIS</span>
        </div>
    </div>

    <nav class="nav-menu">
        <a href="#" class="nav-item active">
            <i class="fas fa-home"></i>
            Dashboard
        </a>

        <a href="{{ route('pds.index') }}" 
           class="nav-item {{ request()->routeIs('pds.*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i>
            Personal Data Sheet
        </a>

        <a href="{{ route('saln.index') }}" 
           class="nav-item {{ request()->routeIs('saln.*') ? 'active' : '' }}">
            <i class="fas fa-balance-scale"></i>
            SALN
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-users"></i>
            Employee Management
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-user-plus"></i>
            Recruitment
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-clipboard-list"></i>
            Attendance Monitoring
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-file-signature"></i>
            Leave & Approvals
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-plane-departure"></i>
            Travel Requests
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-money-check-alt"></i>
            Payroll Records
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-id-card"></i>
            Employee Profiles
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-briefcase"></i>
            Job Positions
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-award"></i>
            Performance Evaluation
        </a>


        <a href="#" class="nav-item">
            <i class="fas fa-archive"></i>
            Employee Records Archive
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-chart-bar"></i>
            HR Analytics
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-cog"></i>
            Settings
        </a>
    </nav>

    <button class="collapse-btn">
        <i class="fas fa-chevron-left"></i>
        Collapse
    </button>
</aside>
