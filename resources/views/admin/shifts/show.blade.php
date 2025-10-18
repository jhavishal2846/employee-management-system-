@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-clock text-success"></i> Shift Details</h2>
            <div>
                <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#assignEmployeeModal">
                    <i class="fas fa-user-plus"></i> Assign Employee
                </button>
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
                                        <span class="badge bg-{{ $assignment->status === 'assigned' ? 'success' : ($assignment->status === 'pending' ? 'warning' : 'danger') }}">
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

<!-- Assign Employee Modal -->
<div class="modal fade" id="assignEmployeeModal" tabindex="-1" aria-labelledby="assignEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignEmployeeModalLabel">Assign Employee to Shift</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.shifts.assign', $shift) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Select Employee <span class="text-danger">*</span></label>
                        <select class="form-control @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" required>
                            <option value="">Choose an employee...</option>
                            @php
                                $assignedEmployeeIds = $shift->employeeShifts->pluck('employee_id')->toArray();
                                $availableEmployees = \App\Models\User::employees()->active()->whereNotIn('id', $assignedEmployeeIds)->get();
                            @endphp
                            @foreach($availableEmployees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->email }})</option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="shift_date" class="form-label">Shift Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('shift_date') is-invalid @enderror"
                               id="shift_date" name="shift_date" value="{{ old('shift_date', date('Y-m-d')) }}" required>
                        @error('shift_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3" placeholder="Optional notes about this assignment">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
