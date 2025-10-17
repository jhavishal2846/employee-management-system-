@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-clock text-success"></i> {{ isset($attendanceLog) ? 'Edit Attendance Record' : 'Add Attendance Record' }}</h2>
            <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Attendance
            </a>
        </div>

        <div class="dashboard-card">
            <div class="card-body">
                <form action="{{ isset($attendanceLog) ? route('admin.attendance.update', $attendanceLog) : route('admin.attendance.store') }}"
                      method="POST">
                    @csrf
                    @if(isset($attendanceLog))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                                <select class="form-control @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id', $attendanceLog->employee_id ?? '') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }} ({{ $employee->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="shift_id" class="form-label">Shift</label>
                                <select class="form-control @error('shift_id') is-invalid @enderror" id="shift_id" name="shift_id">
                                    <option value="">Select Shift (Optional)</option>
                                    @foreach($shifts as $shift)
                                        <option value="{{ $shift->id }}" {{ old('shift_id', $attendanceLog->shift_id ?? '') == $shift->id ? 'selected' : '' }}>
                                            {{ $shift->shift_name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('shift_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="attendance_date" class="form-label">Attendance Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('attendance_date') is-invalid @enderror"
                                       id="attendance_date" name="attendance_date" value="{{ old('attendance_date', $attendanceLog->attendance_date ?? date('Y-m-d')) }}" required>
                                @error('attendance_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="present" {{ old('status', $attendanceLog->status ?? '') == 'present' ? 'selected' : '' }}>Present</option>
                                    <option value="absent" {{ old('status', $attendanceLog->status ?? '') == 'absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="late" {{ old('status', $attendanceLog->status ?? '') == 'late' ? 'selected' : '' }}>Late</option>
                                    <option value="early_leave" {{ old('status', $attendanceLog->status ?? '') == 'early_leave' ? 'selected' : '' }}>Early Leave</option>
                                    <option value="on_break" {{ old('status', $attendanceLog->status ?? '') == 'on_break' ? 'selected' : '' }}>On Break</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="login_time" class="form-label">Login Time</label>
                                <input type="time" class="form-control @error('login_time') is-invalid @enderror"
                                       id="login_time" name="login_time" value="{{ old('login_time', isset($attendanceLog) && $attendanceLog->login_time ? $attendanceLog->login_time->format('H:i') : '') }}">
                                @error('login_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="logout_time" class="form-label">Logout Time</label>
                                <input type="time" class="form-control @error('logout_time') is-invalid @enderror"
                                       id="logout_time" name="logout_time" value="{{ old('logout_time', isset($attendanceLog) && $attendanceLog->logout_time ? $attendanceLog->logout_time->format('H:i') : '') }}">
                                @error('logout_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3">{{ old('notes', $attendanceLog->notes ?? '') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> {{ isset($attendanceLog) ? 'Update Attendance' : 'Create Attendance' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
