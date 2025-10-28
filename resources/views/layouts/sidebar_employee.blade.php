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
        <a href="{{ route('employee.dashboard') }}" 
           class="nav-item {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            Dashboard
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-calendar-check"></i>
            Attendance
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-calendar-times"></i>
            Leave
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-plane"></i>
            Travel
        </a>

        <a href="#" class="nav-item">
            <i class="fas fa-money-bill-wave"></i>
            Payroll
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
    </nav>
    
    <button class="collapse-btn">
        <i class="fas fa-chevron-left"></i>
        Collapse
    </button>
</aside>
