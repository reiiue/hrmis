<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - eHRMIS</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
</head>

<body>
@auth

    <div class="dashboard-container">
        @include('layouts.sidebar_admin')

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header d-flex justify-content-between align-items-center">
                <h1 class="mb-0">Admin Dashboard</h1>
                <div class="user-profile d-flex align-items-center gap-2">
                    <div class="user-avatar">
                        {{ strtoupper(substr(explode('@', Auth::user()->email)[0], 0, 2)) }}
                    </div>
                    <span>{{ Auth::user()->email }}</span>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm ms-3">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </header>

            <!-- Welcome Banner -->
            <div class="welcome-banner mt-3">
                <div class="welcome-content">
                    <h2>Welcome back, {{ Auth::user()->email }}!</h2>
                    <p>Here's what's happening today.</p>
                </div>
                <div class="welcome-date">
                    {{ \Carbon\Carbon::now()->format('l, F j, Y') }}
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid mt-4">
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Days Worked</div>
                        <h3>1</h3>
                        <div class="stat-sublabel">3% attendance rate</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon purple">
                        <i class="fas fa-peso-sign"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">This Month's Pay</div>
                        <h3>₱ 0.00</h3>
                        <div class="stat-sublabel">No upcoming payday</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon yellow">
                        <i class="fas fa-umbrella-beach"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Leave Balance</div>
                        <h3>15</h3>
                        <div class="stat-sublabel">No pending requests</div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="content-grid mt-4">
                <!-- Time Tracking -->
                <div class="card">
                    <h3>Today's Time Tracking</h3>
                    <div class="status-badge">
                        <span>Status:</span>
                        <div class="status-dot"></div>
                        <span>Absent</span>
                    </div>

                    <div class="time-tracking-grid">
                        <div class="time-item">
                            <div class="time-label">Check in</div>
                            <div class="time-value">--:-- --</div>
                            <div class="time-status">Pending</div>
                        </div>
                        <div class="time-item">
                            <div class="time-label">Check out</div>
                            <div class="time-value">--:-- --</div>
                            <div class="time-status">Pending</div>
                        </div>
                    </div>

                    <button class="check-in-btn mt-2">
                        <i class="fas fa-sign-in-alt"></i>
                        Check In Now
                    </button>

                    <div class="hours-summary mt-3">
                        <span>Today's Hours</span>
                        <strong>0h 0m</strong>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="card">
                    <h3><i class="fas fa-calendar-alt"></i> Upcoming Events</h3>
                    <div class="no-events">
                        No upcoming events
                    </div>
                    <a href="#" class="view-all-link">View All Events ></a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-4">
                <h3>Quick Actions</h3>
                <div class="quick-actions">
                    <a href="#" class="quick-action">
                        <div class="quick-action-icon blue">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>Request Leave</div>
                    </a>
                    <a href="#" class="quick-action">
                        <div class="quick-action-icon green">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div>Change Password</div>
                    </a>
                    <a href="#" class="quick-action">
                        <div class="quick-action-icon purple">
                            <i class="fas fa-list"></i>
                        </div>
                        <div>View Payslips</div>
                    </a>
                    <a href="#" class="quick-action">
                        <div class="quick-action-icon red">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <div>Update Profile</div>
                    </a>
                </div>
            </div>
        </main>
    </div>
@else
    <script>window.location.href = "{{ route('login', 'admin') }}";</script>
@endauth
</body>
</html>
