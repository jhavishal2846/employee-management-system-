@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-clock text-success"></i> My Shifts</h2>
            <a href="{{ route('employee.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="dashboard-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Shift Name</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shifts as $shift)
                            <tr>
                                <td>{{ $shift->shift->shift_name }}</td>
                                <td>{{ $shift->shift_date->format('M d, Y') }}</td>
                                <td>{{ $shift->shift->start_time->format('H:i') }} - {{ $shift->shift->end_time->format('H:i') }}</td>
                                <td>{{ $shift->shift->location ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $shift->status === 'accepted' ? 'success' : ($shift->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($shift->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($shift->status === 'pending')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                    @else
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Details
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No shifts assigned.</td>
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
