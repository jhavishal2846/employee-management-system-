@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-calendar-plus text-success"></i> {{ isset($shift) ? 'Edit Shift' : 'Create New Shift' }}</h2>
            <a href="{{ route('admin.shifts.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Shifts
            </a>
        </div>

        <div class="dashboard-card">
            <div class="card-body">
                <form action="{{ isset($shift) ? route('admin.shifts.update', $shift) : route('admin.shifts.store') }}"
                      method="POST">
                    @csrf
                    @if(isset($shift))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="shift_name" class="form-label">Shift Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('shift_name') is-invalid @enderror"
                                       id="shift_name" name="shift_name" value="{{ old('shift_name', $shift->shift_name ?? '') }}" required>
                                @error('shift_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="shift_type" class="form-label">Shift Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('shift_type') is-invalid @enderror" id="shift_type" name="shift_type" required>
                                    <option value="">Select Shift Type</option>
                                    <option value="morning" {{ old('shift_type', $shift->shift_type ?? '') == 'morning' ? 'selected' : '' }}>Morning</option>
                                    <option value="evening" {{ old('shift_type', $shift->shift_type ?? '') == 'evening' ? 'selected' : '' }}>Evening</option>
                                    <option value="night" {{ old('shift_type', $shift->shift_type ?? '') == 'night' ? 'selected' : '' }}>Night</option>
                                    <option value="custom" {{ old('shift_type', $shift->shift_type ?? '') == 'custom' ? 'selected' : '' }}>Custom</option>
                                </select>
                                @error('shift_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                       id="start_time" name="start_time" value="{{ old('start_time', isset($shift) ? $shift->start_time : '') }}" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                       id="end_time" name="end_time" value="{{ old('end_time', isset($shift) ? $shift->end_time : '') }}" required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_capacity" class="form-label">Maximum Capacity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('max_capacity') is-invalid @enderror"
                                       id="max_capacity" name="max_capacity" value="{{ old('max_capacity', $shift->max_capacity ?? '1') }}"
                                       min="1" required>
                                @error('max_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                       id="location" name="location" value="{{ old('location', $shift->location ?? '') }}">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ old('status', $shift->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $shift->status ?? 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3">{{ old('description', $shift->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.shifts.index') }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> {{ isset($shift) ? 'Update Shift' : 'Create Shift' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
