<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - eHRMIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">

</head>
<body>
    <div class="dashboard-container">

        @include('partials.sidebar')

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <h1>Admin Dashboard</h1>
                <div class="user-profile">
                    <div class="user-avatar">
                        {{ strtoupper(
                            substr(explode(' ', Auth::user()->username)[0], 0, 1) .
                            (isset(explode(' ', Auth::user()->username)[1]) ? substr(explode(' ', Auth::user()->username)[1], 0, 1) : '')
                        ) }}
                    </div>
                    <span>{{ Auth::user()->username }}!</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </header>

            <!-- Welcome Banner -->
            <div class="welcome-banner">
                <div class="welcome-content">
                    <h2>Welcome back, {{ Auth::user()->username }}!</h2>
                    <p>Here's what's happening today.</p>
                </div>
                <div class="welcome-date">
                    {{ \Carbon\Carbon::now()->format('l, F j, Y') }}
                </div>
            </div>


            <!-- Stats Grid -->
            <div class="stats-grid">
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
            <div class="content-grid">
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
                        <button class="check-in-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        Check In Now
                    </button>
                    
                    <div class="hours-summary">
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
            <div class="card">
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

        </main>
            

</body>
</html>
