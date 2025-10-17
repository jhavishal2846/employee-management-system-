@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-clock text-success"></i> Shifts Management</h2>
            <a href="{{ route('admin.shifts.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Add Shift
            </a>
        </div>

        <div class="dashboard-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Shift Name</th>
                                <th>Type</th>
                                <th>Time</th>
                                <th>Capacity</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shifts as $shift)
                            <tr>
                                <td>{{ $shift->shift_name }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ ucfirst($shift->shift_type) }}</span>
                                </td>
                                <td>{{ $shift->start_time }} - {{ $shift->end_time }}</td>
                                <td>{{ $shift->max_capacity }}</td>
                                <td>{{ $shift->location ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $shift->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($shift->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.shifts.show', $shift) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.shifts.edit', $shift) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteShift({{ $shift->id }}, '{{ $shift->shift_name }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No shifts found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($shifts->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $shifts->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
