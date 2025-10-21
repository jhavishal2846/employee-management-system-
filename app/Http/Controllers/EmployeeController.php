<?php

namespace App\Http\Controllers;

use App\Models\EmployeeShift;
use App\Models\AttendanceLog;
use App\Models\BreakLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $todayShifts = EmployeeShift::where('employee_id', $user->id)
            ->where('shift_date', today())
            ->whereIn('status', ['accepted', 'assigned'])
            ->with('shift')
            ->get();

        $upcomingShifts = EmployeeShift::where('employee_id', $user->id)
            ->where('shift_date', '>', today())
            ->whereIn('status', ['accepted', 'assigned'])
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

    public function requests(Request $request)
    {
        $user = Auth::user();
        $query = EmployeeShift::where('employee_id', $user->id)
            ->with('shift')
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(15)->appends($request->query());
        $currentStatus = $request->get('status', 'all');

        return view('employee.requests.index', compact('requests', 'currentStatus'));
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

    public function acceptShift(Request $request, EmployeeShift $employeeShift)
    {
        if ($employeeShift->employee_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!in_array($employeeShift->status, ['pending', 'assigned'])) {
            return response()->json(['error' => 'Shift is not available for acceptance'], 400);
        }

        $employeeShift->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        return response()->json(['success' => 'Shift accepted successfully']);
    }

    public function rejectShift(Request $request, EmployeeShift $employeeShift)
    {
        if ($employeeShift->employee_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!in_array($employeeShift->status, ['pending', 'assigned'])) {
            return response()->json(['error' => 'Shift is not available for rejection'], 400);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $employeeShift->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'responded_at' => now(),
        ]);

        return response()->json(['success' => 'Shift rejected successfully']);
    }

    public function clockIn(Request $request)
    {
        $user = Auth::user();
        $today = today();
        $now = now();

        // Check if already clocked in today
        $existingAttendance = AttendanceLog::where('employee_id', $user->id)
            ->where('attendance_date', $today)
            ->first();

        if ($existingAttendance && $existingAttendance->login_time) {
            return response()->json(['error' => 'Already clocked in today'], 400);
        }

        // Get today's assigned shift - must be accepted
        $todayShift = EmployeeShift::where('employee_id', $user->id)
            ->where('shift_date', $today)
            ->where('status', 'accepted')
            ->with('shift')
            ->first();

        if (!$todayShift) {
            return response()->json(['error' => 'No accepted shift found for today'], 400);
        }

        // Validate shift timing - can only clock in during shift hours
        $shift = $todayShift->shift;
        if ($shift) {
            // Create shift times in IST (will use app timezone)
            $shiftStart = Carbon::parse($today->format('Y-m-d') . ' ' . $shift->start_time->format('H:i:s'));
            $shiftEnd = Carbon::parse($today->format('Y-m-d') . ' ' . $shift->end_time->format('H:i:s'));

            // Handle shifts that cross midnight (e.g., 16:00 - 00:00)
            if ($shiftEnd->lt($shiftStart)) {
                $shiftEnd->addDay();
            }

            if ($now->lt($shiftStart)) {
                return response()->json(['error' => 'Cannot clock in before shift start time'], 400);
            }

            if ($now->gt($shiftEnd)) {
                return response()->json(['error' => 'Cannot clock in after shift end time'], 400);
            }
        }

        $attendanceData = [
            'employee_id' => $user->id,
            'attendance_date' => $today,
            'login_time' => $now,
            'status' => 'present',
            'ip_address' => $request->ip(),
            'shift_id' => $todayShift->shift_id,
        ];

        if ($existingAttendance) {
            $existingAttendance->update($attendanceData);
        } else {
            AttendanceLog::create($attendanceData);
        }

        return response()->json(['success' => 'Clocked in successfully']);
    }

    public function clockOut(Request $request)
    {
        try {
            $user = Auth::user();
            $today = today();

            $attendance = AttendanceLog::where('employee_id', $user->id)
                ->where('attendance_date', $today)
                ->whereNotNull('login_time')
                ->whereNull('logout_time')
                ->first();

            if (!$attendance) {
                return response()->json(['error' => 'No active clock-in found'], 400);
            }

            $logoutTime = now();
            $loginTime = Carbon::parse($attendance->login_time);

            // Calculate total hours
            $totalMinutes = $logoutTime->diffInMinutes($loginTime);
            $totalHours = round($totalMinutes / 60, 2);

            // Calculate break duration
            $breakDuration = $attendance->breakLogs()->sum('duration_minutes');

            // Subtract break time from total hours
            $netMinutes = $totalMinutes - $breakDuration;
            $netHours = round($netMinutes / 60, 2);

            // Get shift details for overtime calculation
            $shift = $attendance->shift;
            $standardHours = 8; // Default 8 hours

            if ($shift) {
                // Calculate shift duration
                $shiftStart = Carbon::createFromTimeString($shift->start_time->format('H:i:s'));
                $shiftEnd = Carbon::createFromTimeString($shift->end_time->format('H:i:s'));
                $shiftMinutes = $shiftEnd->diffInMinutes($shiftStart);
                $standardHours = round($shiftMinutes / 60, 2);
            }

            // Calculate overtime
            $overtimeHours = max(0, $netHours - $standardHours);

            $attendance->update([
                'logout_time' => $logoutTime,
                'total_hours_minutes' => $netMinutes,
                'total_hours' => $netHours,
                'break_duration_minutes' => $breakDuration,
                'is_overtime' => $overtimeHours > 0,
                'overtime_hours' => $overtimeHours,
                'status' => $netHours >= ($standardHours * 0.5) ? 'present' : 'early_leave', // Must work at least 50% of shift
            ]);

            return response()->json([
                'success' => 'Clocked out successfully',
                'total_hours' => $netHours,
                'overtime_hours' => $overtimeHours,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Clock out error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'An error occurred while clocking out. Please try again.'], 500);
        }
    }

    public function startBreak(Request $request)
    {
        $user = Auth::user();
        $today = today();

        $attendance = AttendanceLog::where('employee_id', $user->id)
            ->where('attendance_date', $today)
            ->whereNotNull('login_time')
            ->whereNull('logout_time')
            ->first();

        if (!$attendance) {
            return response()->json(['error' => 'No active shift found'], 400);
        }

        // Check if already on break
        $activeBreak = $attendance->activeBreak;
        if ($activeBreak) {
            return response()->json(['error' => 'Already on break'], 400);
        }

        $request->validate([
            'break_type' => 'required|in:lunch,short,other',
        ]);

        BreakLog::create([
            'attendance_log_id' => $attendance->id,
            'break_start' => now(),
            'break_type' => $request->break_type,
        ]);

        // Update attendance status to on_break
        $attendance->update(['status' => 'on_break']);

        return response()->json(['success' => 'Break started successfully']);
    }

    public function endBreak(Request $request)
    {
        $user = Auth::user();
        $today = today();

        $attendance = AttendanceLog::where('employee_id', $user->id)
            ->where('attendance_date', $today)
            ->whereNotNull('login_time')
            ->whereNull('logout_time')
            ->first();

        if (!$attendance) {
            return response()->json(['error' => 'No active shift found'], 400);
        }

        $activeBreak = $attendance->activeBreak;
        if (!$activeBreak) {
            return response()->json(['error' => 'No active break found'], 400);
        }

        $breakEnd = now();
        $breakStart = Carbon::parse($activeBreak->break_start);
        $durationMinutes = $breakEnd->diffInMinutes($breakStart);

        $activeBreak->update([
            'break_end' => $breakEnd,
            'duration_minutes' => $durationMinutes,
        ]);

        // Update attendance status back to present
        $attendance->update(['status' => 'present']);

        return response()->json([
            'success' => 'Break ended successfully',
            'break_duration' => $durationMinutes,
        ]);
    }

    public function getAttendanceStatus(Request $request)
    {
        $user = Auth::user();
        $today = today();

        $attendance = AttendanceLog::where('employee_id', $user->id)
            ->where('attendance_date', $today)
            ->first();

        $response = ['status' => 'not_clocked_in'];

        if ($attendance) {
            if ($attendance->logout_time) {
                $response = [
                    'status' => 'clocked_out',
                    'login_time' => $attendance->login_time->format('H:i'),
                    'logout_time' => $attendance->logout_time->format('H:i'),
                    'total_hours' => $attendance->total_hours,
                ];
            } elseif ($attendance->activeBreak) {
                $response = [
                    'status' => 'on_break',
                    'login_time' => $attendance->login_time->format('H:i'),
                    'break_start_time' => $attendance->activeBreak->break_start->format('H:i'),
                    'break_start_timestamp' => $attendance->activeBreak->break_start->timestamp * 1000,
                ];
            } else {
                $response = [
                    'status' => 'clocked_in',
                    'login_time' => $attendance->login_time->format('H:i'),
                ];
            }
        }

        return response()->json($response);
    }

    public function showShift(EmployeeShift $employeeShift)
    {
        if ($employeeShift->employee_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $employeeShift->load(['shift', 'employee']);

        return view('employee.shifts.show', compact('employeeShift'));
    }
}
