<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_log_id',
        'break_start',
        'break_end',
        'duration_minutes',
        'break_type',
        'notes',
    ];

    protected $casts = [
        'break_start' => 'datetime',
        'break_end' => 'datetime',
    ];

    public function attendanceLog()
    {
        return $this->belongsTo(AttendanceLog::class);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('break_end');
    }

    public function scopePaginateBreaks($query, $perPage = 15)
    {
        return $query->with(['attendanceLog.employee'])->latest()->paginate($perPage);
    }

    public function scopePaginateForEmployee($query, $employeeId, $perPage = 15)
    {
        return $query->whereHas('attendanceLog', function ($q) use ($employeeId) {
            $q->where('employee_id', $employeeId);
        })->with('attendanceLog')->latest()->paginate($perPage);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('break_start', $date);
    }
}
