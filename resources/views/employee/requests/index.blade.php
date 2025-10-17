@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-paper-plane text-success"></i> My Requests</h2>
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
                                <th>Request Type</th>
                                <th>Description</th>
                                <th>Date Submitted</th>
                                <th>Status</th>
                                <th>Response</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $request)
                            <tr>
                                <td>{{ ucfirst($request->request_type ?? 'Shift') }}</td>
                                <td>{{ $request->description ?? 'Shift request for ' . $request->shift_date->format('M d, Y') }}</td>
                                <td>{{ $request->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $request->status === 'accepted' ? 'success' : ($request->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>{{ $request->rejection_reason ?? 'N/A' }}</td>
                                <td>
                                    @if($request->status === 'pending')
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
                                <td colspan="6" class="text-center">No requests found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($requests->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $requests->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
