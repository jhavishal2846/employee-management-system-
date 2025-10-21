<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShiftRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'shift_id',
        'requested_date',
        'request_type',
        'status',
        'reason',
        'approved_by',
        'approved_at',
        'admin_notes',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopePaginateRequests($query, $perPage = 15)
    {
        return $query->with(['employee', 'shift', 'approver'])->latest()->paginate($perPage);
    }

    public function scopePaginateForEmployee($query, $employeeId, $perPage = 15)
    {
        return $query->where('employee_id', $employeeId)->with(['shift', 'approver'])->latest()->paginate($perPage);
    }
}
