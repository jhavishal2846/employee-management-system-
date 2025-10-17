@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-calendar-check text-success"></i> Attendance Details</h2>
            <div>
                <a href="{{ route('admin.attendance.edit', $attendanceLog) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit"></i> Edit Record
                </a>
                <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="mb-4">Attendance Information</h4>
                        <div class="mb-3">
                            <strong>Employee:</strong> {{ $attendanceLog->employee->name }}
                        </div>
                        <div class="mb-3">
                            <strong>Email:</strong> {{ $attendanceLog->employee->email }}
                        </div>
                        <div class="mb-3">
                            <strong>Attendance Date:</strong> {{ $attendanceLog->attendance_date->format('M d, Y') }}
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong>
                            <span class="badge bg-{{ $attendanceLog->status === 'present' ? 'success' : ($attendanceLog->status === 'absent' ? 'danger' : 'warning') }}">
                                {{ ucfirst($attendanceLog->status) }}
                            </span>
                        </div>
                        @if($attendanceLog->login_time)
                            <div class="mb-3">
                                <strong>Login Time:</strong> {{ $attendanceLog->login_time->format('H:i') }}
                            </div>
                        @endif
                        @if($attendanceLog->logout_time)
                            <div class="mb-3">
                                <strong>Logout Time:</strong> {{ $attendanceLog->logout_time->format('H:i') }}
                            </div>
                        @endif
                        @if($attendanceLog->total_hours)
                            <div class="mb-3">
                                <strong>Total Hours:</strong> {{ number_format($attendanceLog->total_hours, 2) }}
                            </div>
                        @endif
                        <div class="mb-3">
                            <strong>Notes:</strong> {{ $attendanceLog->notes ?? 'N/A' }}
                        </div>
                        <div class="mb-3">
                            <strong>Created:</strong> {{ $attendanceLog->created_at->format('M d, Y H:i') }}
                        </div>
                        @if($attendanceLog->updated_at != $attendanceLog->created_at)
                            <div class="mb-3">
                                <strong>Last Updated:</strong> {{ $attendanceLog->updated_at->format('M d, Y H:i') }}
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h4 class="mb-4">Shift Information</h4>
                        @if($attendanceLog->shift)
                            <div class="mb-3">
                                <strong>Shift Name:</strong> {{ $attendanceLog->shift->shift_name }}
                            </div>
                            <div class="mb-3">
                                <strong>Shift Type:</strong>
                                <span class="badge bg-primary">{{ ucfirst($attendanceLog->shift->shift_type) }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>Scheduled Time:</strong> {{ $attendanceLog->shift->start_time }} - {{ $attendanceLog->shift->end_time }}
                            </div>
                            <div class="mb-3">
                                <strong>Location:</strong> {{ $attendanceLog->shift->location ?? 'N/A' }}
                            </div>
                        @else
                            <p class="text-muted">No shift assigned for this attendance record</p>
                        @endif

                        <h4 class="mb-4 mt-4">Approval Information</h4>
                        @if($attendanceLog->approver)
                            <div class="mb-3">
                                <strong>Approved By:</strong> {{ $attendanceLog->approver->name }}
                            </div>
                            <div class="mb-3">
                                <strong>Approval Date:</strong> {{ $attendanceLog->updated_at->format('M d, Y H:i') }}
                            </div>
                        @else
                            <p class="text-muted">Not yet approved</p>
                        @endif

                        <h4 class="mb-4 mt-4">Employee Statistics</h4>
                        @php
                            $employeeStats = [
                                'total_days' => $attendanceLog->employee->attendanceLogs()->count(),
                                'present_days' => $attendanceLog->employee->attendanceLogs()->where('status', 'present')->count(),
                                'absent_days' => $attendanceLog->employee->attendanceLogs()->where('status', 'absent')->count(),
                            ];
                            $attendance_rate = $employeeStats['total_days'] > 0 ? round(($employeeStats['present_days'] / $employeeStats['total_days']) * 100, 1) : 0;
                        @endphp
                        <div class="mb-3">
                            <strong>Total Records:</strong> {{ $employeeStats['total_days'] }}
                        </div>
                        <div class="mb-3">
                            <strong>Present Days:</strong> {{ $employeeStats['present_days'] }}
                        </div>
                        <div class="mb-3">
                            <strong>Absent Days:</strong> {{ $employeeStats['absent_days'] }}
                        </div>
                        <div class="mb-3">
                            <strong>Attendance Rate:</strong>
                            <span class="badge bg-{{ $attendance_rate >= 80 ? 'success' : ($attendance_rate >= 60 ? 'warning' : 'danger') }}">
                                {{ $attendance_rate }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
