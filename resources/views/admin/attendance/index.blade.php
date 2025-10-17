@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-calendar-check text-success"></i> Attendance Management</h2>
            <a href="{{ route('admin.attendance.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Add Manual Entry
            </a>
        </div>

        <div class="dashboard-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Login Time</th>
                                <th>Logout Time</th>
                                <th>Total Hours</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendanceLogs as $log)
                            <tr>
                                <td>{{ $log->employee->name }}</td>
                                <td>{{ $log->attendance_date->format('M d, Y') }}</td>
                                <td>{{ $log->login_time ? $log->login_time->format('H:i') : 'N/A' }}</td>
                                <td>{{ $log->logout_time ? $log->logout_time->format('H:i') : 'N/A' }}</td>
                                <td>{{ $log->total_hours ? number_format($log->total_hours, 2) : 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $log->status === 'present' ? 'success' : ($log->status === 'absent' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($log->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.attendance.show', $log) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.attendance.edit', $log) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteAttendance({{ $log->id }}, '{{ $log->employee->name }} - {{ $log->attendance_date->format('M d, Y') }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
