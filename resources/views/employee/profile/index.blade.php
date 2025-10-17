@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-user text-success"></i> My Profile</h2>
            <a href="{{ route('employee.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Profile Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Full Name</label>
                                    <p>{{ auth()->user()->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <p>{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Phone</label>
                                    <p>{{ auth()->user()->phone ?? 'Not provided' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Department</label>
                                    <p>{{ auth()->user()->department ?? 'Not assigned' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Hourly Rate</label>
                                    <p>${{ number_format((float) auth()->user()->hourly_rate, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <p>
                                        <span class="badge bg-{{ auth()->user()->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst(auth()->user()->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="dashboard-card">
                    <div class="card-body text-center">
                        <div class="profile-avatar mb-3">
                            <i class="fas fa-user-circle fa-5x text-success"></i>
                        </div>
                        <h4>{{ auth()->user()->name }}</h4>
                        <p class="text-muted">{{ auth()->user()->email }}</p>
                        <span class="badge bg-success">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                </div>

                <div class="dashboard-card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Quick Stats</h5>
                        <div class="row text-center">
                            <div class="col-6">
                                <h3 class="text-success">{{ $totalShifts ?? 0 }}</h3>
                                <small>Total Shifts</small>
                            </div>
                            <div class="col-6">
                                <h3 class="text-success">{{ number_format($totalHours ?? 0, 1) }}</h3>
                                <small>Total Hours</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-control" value="{{ auth()->user()->phone }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->department }}">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection
