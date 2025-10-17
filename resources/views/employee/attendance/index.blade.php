@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-calendar-check text-success"></i> My Attendance</h2>
            <a href="{{ route('employee.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Current Status Card -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="card-body">
                        <h3>{{ number_format($thisMonthHours, 1) }}</h3>
                        <p>This Month Hours</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="card-body">
                        <h3>{{ $attendanceLogs->where('status', 'present')->count() }}</h3>
                        <p>Present Days</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="card-body">
                        <h3>{{ $attendanceLogs->where('status', 'absent')->count() }}</h3>
                        <p>Absent Days</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="card-body">
                        <h3>{{ $attendanceLogs->where('is_overtime', true)->count() }}</h3>
                        <p>Overtime Days</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Login Time</th>
                                <th>Logout Time</th>
                                <th>Total Hours</th>
                                <th>Break Duration</th>
                                <th>Status</th>
                                <th>Overtime</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendanceLogs as $log)
                            <tr>
                                <td>{{ $log->attendance_date->format('M d, Y') }}</td>
                                <td>{{ $log->login_time ? $log->login_time->format('H:i') : 'N/A' }}</td>
                                <td>{{ $log->logout_time ? $log->logout_time->format('H:i') : 'N/A' }}</td>
                                <td>{{ $log->total_hours ? number_format($log->total_hours, 2) : 'N/A' }}</td>
                                <td>{{ $log->break_duration_minutes ? $log->break_duration_minutes . ' min' : 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $log->status === 'present' ? 'success' : ($log->status === 'absent' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($log->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($log->is_overtime)
                                    <span class="badge bg-info">{{ number_format($log->overtime_hours, 2) }}h</span>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No attendance records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($attendanceLogs->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $attendanceLogs->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
