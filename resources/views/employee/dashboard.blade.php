@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Employee Dashboard</h1>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stats-card dashboard-card">
            <div class="card-body">
                <h3>{{ $todayShifts->count() }}</h3>
                <p>Today's Shifts</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stats-card dashboard-card">
            <div class="card-body">
                <h3>{{ $pendingRequests->count() }}</h3>
                <p>Pending Requests</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stats-card dashboard-card">
            <div class="card-body">
                <h3>{{ number_format($thisMonthHours, 1) }}</h3>
                <p>This Month Hours</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stats-card dashboard-card">
            <div class="card-body">
                <h3>{{ $upcomingShifts->count() }}</h3>
                <p>Upcoming Shifts</p>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Content -->
<div class="row">
    <!-- Today's Shifts -->
    <div class="col-md-6 mb-4">
        <div class="card dashboard-card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Today's Shifts</h5>
            </div>
            <div class="card-body">
                @if($todayShifts->count() > 0)
                    @foreach($todayShifts as $shift)
                        <div class="alert alert-info mb-2">
                            <strong>{{ $shift->shift->shift_name }}</strong><br>
                            <small>{{ $shift->shift->start_time->format('H:i') }} - {{ $shift->shift->end_time->format('H:i') }}</small><br>
                            <small class="text-muted">{{ $shift->shift->location }}</small>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No shifts scheduled for today.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Upcoming Shifts -->
    <div class="col-md-6 mb-4">
        <div class="card dashboard-card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Upcoming Shifts</h5>
            </div>
            <div class="card-body">
                @if($upcomingShifts->count() > 0)
                    @foreach($upcomingShifts as $shift)
                        <div class="alert alert-secondary mb-2">
                            <strong>{{ $shift->shift->shift_name }}</strong><br>
                            <small>{{ $shift->shift_date->format('M d, Y') }}</small><br>
                            <small>{{ $shift->shift->start_time->format('H:i') }} - {{ $shift->shift->end_time->format('H:i') }}</small>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No upcoming shifts scheduled.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Attendance -->
<div class="row">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Recent Attendance</h5>
            </div>
            <div class="card-body">
                @if($recentAttendance->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Hours Worked</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttendance as $attendance)
                                    <tr>
                                        <td>{{ $attendance->attendance_date->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'absent' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $attendance->total_hours ? number_format($attendance->total_hours, 1) . 'h' : '-' }}</td>
                                        <td>{{ $attendance->notes ?: '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No attendance records found.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('employee.attendance.index') }}" class="btn btn-primary w-100">
                            <i class="fas fa-clock"></i> View Attendance
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('employee.shifts.index') }}" class="btn btn-primary w-100">
                            <i class="fas fa-calendar"></i> View Shifts
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('employee.requests.index') }}" class="btn btn-primary w-100">
                            <i class="fas fa-list"></i> View Requests
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('employee.profile.edit') }}" class="btn btn-primary w-100">
                            <i class="fas fa-user"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
