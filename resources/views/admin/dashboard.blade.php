@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary: #3b82f6;
        --primary-dark: #1e40af;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --secondary: #8b5cf6;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .page-header {
        margin-bottom: 2rem;
        animation: slideDown 0.5s ease-out;
    }

    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        color: #6b7280;
        font-size: 1rem;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Stats Cards */
    .stats-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
        min-height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
    }

    .stats-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
    }

    .stats-card .card-body {
        text-align: center;
        width: 100%;
        padding: 1.5rem;
    }

    .stats-card h3 {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .stats-card p {
        color: #6b7280;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .stats-card.blue h3 { color: #3b82f6; }
    .stats-card.purple h3 { color: #8b5cf6; }
    .stats-card.orange h3 { color: #f59e0b; }
    .stats-card.green h3 { color: #10b981; }

    /* Dashboard Cards */
    .dashboard-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }

    .dashboard-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .dashboard-card .card-header {
        background: linear-gradient(135deg, #1f2937 0%, #374151 100%) !important;
        border: none;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .dashboard-card .card-header h5 {
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .dashboard-card .card-body {
        padding: 1.5rem;
        background: white;
    }

    /* Table Styles */
    .table {
        font-size: 0.95rem;
    }

    .table thead th {
        background: #f3f4f6;
        color: #374151;
        font-weight: 600;
        border: none;
        padding: 1rem;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .table tbody td {
        padding: 1rem;
        border: none;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: #f9fafb;
        transition: background 0.2s ease;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Badge Styles */
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .badge.bg-success {
        background: #d1fae5 !important;
        color: #065f46;
    }

    .badge.bg-danger {
        background: #fee2e2 !important;
        color: #7f1d1d;
    }

    .badge.bg-warning {
        background: #fef3c7 !important;
        color: #92400e;
    }

    /* Action Buttons */
    .btn-action {
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-success {
        background: var(--success);
        color: white;
    }

    .btn-success:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-danger {
        background: var(--danger);
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    /* Quick Actions */
    .quick-action-btn {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 1.5rem;
        text-decoration: none;
        color: #1f2937;
        font-weight: 600;
        transition: all 0.3s ease;
        display: block;
        text-align: center;
    }

    .quick-action-btn:hover {
        border-color: var(--primary);
        background: #eff6ff;
        color: var(--primary);
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.15);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #9ca3af;
    }

    .empty-state p {
        font-size: 0.95rem;
    }

    /* Request Card */
    .request-card {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-left: 4px solid var(--primary);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .request-card:hover {
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .request-card-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 0.75rem;
    }

    .request-card-header strong {
        color: #1f2937;
        font-size: 0.95rem;
    }

    .request-card-body {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 0.75rem;
    }

    .request-actions {
        display: flex;
        gap: 0.5rem;
    }

    .request-actions .btn {
        flex: 1;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }
</style>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1>Admin Dashboard</h1>
            <p>Welcome back! Here's your office management overview.</p>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stats-card blue">
            <div class="card-body">
                <h3>{{ $totalEmployees }}</h3>
                <p>Total Employees</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stats-card purple">
            <div class="card-body">
                <h3>{{ $totalShifts }}</h3>
                <p>Active Shifts</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stats-card orange">
            <div class="card-body">
                <h3>{{ $pendingShiftRequests }}</h3>
                <p>Pending Requests</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stats-card green">
            <div class="card-body">
                <h3>{{ $todayAttendance }}</h3>
                <p>Today's Attendance</p>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Content -->
<div class="row">
    <!-- Recent Attendance -->
    <div class="col-lg-6 mb-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">Recent Attendance</h5>
            </div>
            <div class="card-body">
                @if($recentAttendance->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttendance as $attendance)
                                    <tr>
                                        <td>{{ $attendance->employee->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'absent' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $attendance->attendance_date->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <p>No recent attendance records found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Pending Shift Requests -->
    <div class="col-lg-6 mb-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">Pending Shift Requests</h5>
            </div>
            <div class="card-body">
                @if($shiftRequests->count() > 0)
                    @foreach($shiftRequests as $request)
                        <div class="request-card">
                            <div class="request-card-header">
                                <strong>{{ $request->employee->name }}</strong>
                            </div>
                            <div class="request-card-body">
                                <div><strong>{{ $request->shift->shift_name }}</strong></div>
                                <small>{{ $request->shift_date->format('M d, Y') }}</small>
                            </div>
                            <div class="request-actions">
                                <button class="btn btn-success btn-action">Approve</button>
                                <button class="btn btn-danger btn-action">Reject</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <p>No pending shift requests.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.employees.index') }}" class="quick-action-btn">👥 Manage Employees</a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.shifts.create') }}" class="quick-action-btn">📅 Create Shift</a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.reports.index') }}" class="quick-action-btn">📊 View Reports</a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.attendance.index') }}" class="quick-action-btn">✓ Attendance Overview</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
