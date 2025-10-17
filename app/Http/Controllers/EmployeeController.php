<?php

namespace App\Http\Controllers;

use App\Models\EmployeeShift;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $todayShifts = EmployeeShift::where('employee_id', $user->id)
            ->where('shift_date', today())
            ->where('status', 'accepted')
            ->with('shift')
            ->get();

        $upcomingShifts = EmployeeShift::where('employee_id', $user->id)
            ->where('shift_date', '>', today())
            ->where('status', 'accepted')
            ->with('shift')
            ->orderBy('shift_date')
            ->take(5)
            ->get();

        $pendingRequests = EmployeeShift::where('employee_id', $user->id)
            ->where('status', 'pending')
            ->with('shift')
            ->get();

        $recentAttendance = AttendanceLog::where('employee_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $thisMonthHours = AttendanceLog::where('employee_id', $user->id)
            ->whereMonth('attendance_date', now()->month)
            ->whereYear('attendance_date', now()->year)
            ->sum('total_hours');

        return view('employee.dashboard', compact(
            'todayShifts',
            'upcomingShifts',
            'pendingRequests',
            'recentAttendance',
            'thisMonthHours'
        ));
    }

    public function shifts()
    {
        $user = Auth::user();
        $shifts = EmployeeShift::where('employee_id', $user->id)
            ->with('shift')
            ->orderBy('shift_date', 'desc')
            ->paginate(15);
        return view('employee.shifts.index', compact('shifts'));
    }

    public function attendance()
    {
        $user = Auth::user();
        $attendanceLogs = AttendanceLog::where('employee_id', $user->id)
            ->latest()
            ->paginate(15);

        $thisMonthHours = AttendanceLog::where('employee_id', $user->id)
            ->whereMonth('attendance_date', now()->month)
            ->whereYear('attendance_date', now()->year)
            ->sum('total_hours');

        return view('employee.attendance.index', compact('attendanceLogs', 'thisMonthHours'));
    }

    public function requests()
    {
        $user = Auth::user();
        $requests = EmployeeShift::where('employee_id', $user->id)
            ->with('shift')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('employee.requests.index', compact('requests'));
    }

    public function profile()
    {
        $user = Auth::user();

        $totalShifts = EmployeeShift::where('employee_id', $user->id)
            ->where('status', 'accepted')
            ->count();

        $totalHours = AttendanceLog::where('employee_id', $user->id)
            ->sum('total_hours');

        return view('employee.profile.index', compact('user', 'totalShifts', 'totalHours'));
    }
}
