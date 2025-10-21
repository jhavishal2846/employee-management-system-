<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'period_start',
        'period_end',
        'total_hours',
        'regular_hours',
        'overtime_hours',
        'hourly_rate',
        'regular_pay',
        'overtime_pay',
        'deductions',
        'total_pay',
        'days_worked',
        'attendance_rate',
        'status',
        'payment_status',
        'paid_at',
        'generated_by',
        'notes',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'total_hours' => 'decimal:2',
        'regular_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'regular_pay' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'deductions' => 'decimal:2',
        'total_pay' => 'decimal:2',
        'attendance_rate' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function generator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeGenerated($query)
    {
        return $query->where('status', 'generated');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePendingPayment($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopePaidPayment($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePaginateReports($query, $perPage = 15)
    {
        return $query->with(['employee', 'generator'])->latest()->paginate($perPage);
    }

    public function scopePaginateForEmployee($query, $employeeId, $perPage = 15)
    {
        return $query->where('employee_id', $employeeId)->with('generator')->latest()->paginate($perPage);
    }
}
