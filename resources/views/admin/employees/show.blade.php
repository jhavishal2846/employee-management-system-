@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-user text-success"></i> Employee Details</h2>
            <div>
                <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit"></i> Edit Employee
                </a>
                <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="mb-4">Personal Information</h4>
                        <div class="mb-3">
                            <strong>Name:</strong> {{ $employee->name }}
                        </div>
                        <div class="mb-3">
                            <strong>Email:</strong> {{ $employee->email }}
                        </div>
                        <div class="mb-3">
                            <strong>Phone:</strong> {{ $employee->phone ?? 'N/A' }}
                        </div>
                        <div class="mb-3">
                            <strong>Department:</strong> {{ $employee->department ?? 'N/A' }}
                        </div>
                        <div class="mb-3">
                            <strong>Hourly Rate:</strong> ${{ number_format($employee->hourly_rate, 2) }}
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong>
                            <span class="badge bg-{{ $employee->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <strong>Role:</strong> {{ ucfirst($employee->role) }}
                        </div>
                        <div class="mb-3">
                            <strong>Joined:</strong> {{ $employee->created_at->format('M d, Y') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4 class="mb-4">Shift Information</h4>
                        @if($employee->employeeShifts->count() > 0)
                            @foreach($employee->employeeShifts as $shift)
                                <div class="mb-3 p-3 border rounded">
                                    <div><strong>Shift:</strong> {{ $shift->shift->shift_name }}</div>
                                    <div><strong>Type:</strong> {{ ucfirst($shift->shift->shift_type) }}</div>
                                    <div><strong>Time:</strong> {{ $shift->shift->start_time }} - {{ $shift->shift->end_time }}</div>
                                    <div><strong>Status:</strong>
                                        <span class="badge bg-{{ $shift->status === 'assigned' ? 'success' : 'warning' }}">
                                            {{ ucfirst($shift->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No shifts assigned</p>
                        @endif

                        <h4 class="mb-4 mt-4">Recent Attendance</h4>
                        @php
                            $recentAttendance = $employee->attendanceLogs()->latest()->take(5)->get();
                        @endphp
                        @if($recentAttendance->count() > 0)
                            @foreach($recentAttendance as $attendance)
                                <div class="mb-2 p-2 border rounded">
                                    <div><strong>Date:</strong> {{ $attendance->attendance_date->format('M d, Y') }}</div>
                                    <div><strong>Status:</strong>
                                        <span class="badge bg-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'absent' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </div>
                                    @if($attendance->login_time)
                                        <div><strong>Login:</strong> {{ $attendance->login_time->format('H:i') }}</div>
                                    @endif
                                    @if($attendance->logout_time)
                                        <div><strong>Logout:</strong> {{ $attendance->logout_time->format('H:i') }}</div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No attendance records</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
