@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-clock text-success"></i> Shift Details</h2>
            <div>
                <a href="{{ route('admin.shifts.edit', $shift) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit"></i> Edit Shift
                </a>
                <a href="{{ route('admin.shifts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="mb-4">Shift Information</h4>
                        <div class="mb-3">
                            <strong>Shift Name:</strong> {{ $shift->shift_name }}
                        </div>
                        <div class="mb-3">
                            <strong>Shift Type:</strong>
                            <span class="badge bg-primary">{{ ucfirst($shift->shift_type) }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Start Time:</strong> {{ $shift->start_time }}
                        </div>
                        <div class="mb-3">
                            <strong>End Time:</strong> {{ $shift->end_time }}
                        </div>
                        <div class="mb-3">
                            <strong>Max Capacity:</strong> {{ $shift->max_capacity }}
                        </div>
                        <div class="mb-3">
                            <strong>Location:</strong> {{ $shift->location ?? 'N/A' }}
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong>
                            <span class="badge bg-{{ $shift->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($shift->status) }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <strong>Description:</strong> {{ $shift->description ?? 'N/A' }}
                        </div>
                        <div class="mb-3">
                            <strong>Created:</strong> {{ $shift->created_at->format('M d, Y') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4 class="mb-4">Assigned Employees</h4>
                        @if($shift->employeeShifts->count() > 0)
                            @foreach($shift->employeeShifts as $assignment)
                                <div class="mb-3 p-3 border rounded">
                                    <div><strong>Name:</strong> {{ $assignment->employee->name }}</div>
                                    <div><strong>Email:</strong> {{ $assignment->employee->email }}</div>
                                    <div><strong>Status:</strong>
                                        <span class="badge bg-{{ $assignment->status === 'assigned' ? 'success' : 'warning' }}">
                                            {{ ucfirst($assignment->status) }}
                                        </span>
                                    </div>
                                    @if($assignment->shift_date)
                                        <div><strong>Assigned Date:</strong> {{ $assignment->shift_date->format('M d, Y') }}</div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No employees assigned to this shift</p>
                        @endif

                        <h4 class="mb-4 mt-4">Recent Attendance</h4>
                        @php
                            $recentAttendance = $shift->employeeShifts()
                                ->with(['employee.attendanceLogs' => function($query) {
                                    $query->latest()->take(3);
                                }])
                                ->get()
                                ->pluck('employee.attendanceLogs')
                                ->flatten()
                                ->sortByDesc('attendance_date')
                                ->take(5);
                        @endphp
                        @if($recentAttendance->count() > 0)
                            @foreach($recentAttendance as $attendance)
                                <div class="mb-2 p-2 border rounded">
                                    <div><strong>Employee:</strong> {{ $attendance->employee->name }}</div>
                                    <div><strong>Date:</strong> {{ $attendance->attendance_date->format('M d, Y') }}</div>
                                    <div><strong>Status:</strong>
                                        <span class="badge bg-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'absent' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No recent attendance records</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
