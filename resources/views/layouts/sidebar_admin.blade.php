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
        <a href="{{ route('admin.dashboard') }}" 
           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
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

        <a href="{{ route('admin.employee.records') }}" class="nav-item nav-link">
            <i class="fas fa-users "></i>
            Employee Records
        </a>


        <a href="#" class="nav-item">
            <i class="fas fa-user-shield"></i>
            HR Management
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-briefcase"></i>
            Department Management
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-layer-group"></i>
            Role & Access Control
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-tasks"></i>
            Job Positions
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-clipboard-list"></i>
            Reports
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-cogs"></i>
            System Settings
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-database"></i>
            Data Management
        </a>

        <a href="{{ route('admin.users.index') }}" class="nav-item">
            <i class="fas fa-user-cog"></i>
            User Accounts
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-chart-line"></i>
            Analytics
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-envelope"></i>
            Notifications
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-question-circle"></i>
            Help & Support
        </a>
    </nav>

    <button class="collapse-btn">
        <i class="fas fa-chevron-left"></i>
        Collapse
    </button>
</aside>
