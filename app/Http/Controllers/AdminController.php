<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shift;
use App\Models\EmployeeShift;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalEmployees = User::employees()->active()->count();
        $totalShifts = Shift::active()->count();
        $pendingShiftRequests = EmployeeShift::pending()->count();
        $todayAttendance = AttendanceLog::where('attendance_date', today())->count();

        $recentAttendance = AttendanceLog::with(['employee', 'shift'])
            ->latest()
            ->take(10)
            ->get();

        $shiftRequests = EmployeeShift::with(['employee', 'shift'])
            ->pending()
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalEmployees',
            'totalShifts',
            'pendingShiftRequests',
            'todayAttendance',
            'recentAttendance',
            'shiftRequests'
        ));
    }

    // Employees CRUD
    public function employees()
    {
        $employees = User::employees()->with('employeeShifts.shift')->paginate(15);
        return view('admin.employees.index', compact('employees'));
    }

    public function createEmployee()
    {
        return view('admin.employees.form');
    }

    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'department' => $request->department,
            'hourly_rate' => $request->hourly_rate,
            'role' => 'employee',
        ]);

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    public function showEmployee(User $employee)
    {
        return view('admin.employees.show', compact('employee'));
    }

    public function editEmployee(User $employee)
    {
        return view('admin.employees.form', compact('employee'));
    }

    public function updateEmployee(Request $request, User $employee)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,blocked',
        ]);

        $employee->update($request->only(['name', 'email', 'phone', 'department', 'hourly_rate', 'status']));

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroyEmployee(User $employee)
    {
        $employee->delete();
        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully.');
    }

    // Shifts CRUD
    public function shifts()
    {
        $shifts = Shift::with('employeeShifts.employee')->paginate(15);
        return view('admin.shifts.index', compact('shifts'));
    }

    public function createShift()
    {
        return view('admin.shifts.form');
    }

    public function storeShift(Request $request)
    {
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'shift_type' => 'required|in:morning,evening,night,custom',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
        ]);

        Shift::create($request->all());

        return redirect()->route('admin.shifts.index')->with('success', 'Shift created successfully.');
    }

    public function showShift(Shift $shift)
    {
        return view('admin.shifts.show', compact('shift'));
    }

    public function editShift(Shift $shift)
    {
        return view('admin.shifts.form', compact('shift'));
    }

    public function updateShift(Request $request, Shift $shift)
    {
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'shift_type' => 'required|in:morning,evening,night,custom',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $shift->update($request->all());

        return redirect()->route('admin.shifts.index')->with('success', 'Shift updated successfully.');
    }

    public function destroyShift(Shift $shift)
    {
        $shift->delete();
        return redirect()->route('admin.shifts.index')->with('success', 'Shift deleted successfully.');
    }

    // Attendance CRUD
    public function attendance()
    {
        $attendanceLogs = AttendanceLog::with(['employee', 'shift'])
            ->latest()
            ->paginate(15);
        return view('admin.attendance.index', compact('attendanceLogs'));
    }

    public function createAttendance()
    {
        $employees = User::employees()->active()->get();
        $shifts = Shift::active()->get();
        return view('admin.attendance.form', compact('employees', 'shifts'));
    }

    public function storeAttendance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'shift_id' => 'nullable|exists:shifts,id',
            'attendance_date' => 'required|date',
            'login_time' => 'nullable|date_format:H:i',
            'logout_time' => 'nullable|date_format:H:i|after:login_time',
            'status' => 'required|in:present,absent,late,early_leave,on_break',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();

        // Calculate total hours if both login and logout times are provided
        if ($request->login_time && $request->logout_time) {
            $loginTime = \Carbon\Carbon::createFromFormat('H:i', $request->login_time);
            $logoutTime = \Carbon\Carbon::createFromFormat('H:i', $request->logout_time);

            // Calculate total hours
            $totalHours = $logoutTime->diffInMinutes($loginTime) / 60;
            $data['total_hours'] = round($totalHours, 2);
        }

        AttendanceLog::create($data);

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance record created successfully.');
    }

    public function showAttendance(AttendanceLog $attendanceLog)
    {
        $attendanceLog->load(['employee', 'shift', 'approver']);
        return view('admin.attendance.show', compact('attendanceLog'));
    }

    public function editAttendance(AttendanceLog $attendanceLog)
    {
        $employees = User::employees()->active()->get();
        $shifts = Shift::active()->get();
        return view('admin.attendance.form', compact('attendanceLog', 'employees', 'shifts'));
    }

    public function updateAttendance(Request $request, AttendanceLog $attendanceLog)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'shift_id' => 'nullable|exists:shifts,id',
            'attendance_date' => 'required|date',
            'login_time' => 'nullable|date_format:H:i',
            'logout_time' => 'nullable|date_format:H:i|after:login_time',
            'status' => 'required|in:present,absent,late,early_leave,on_break',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();

        // Calculate total hours if both login and logout times are provided
        if ($request->login_time && $request->logout_time) {
            $loginTime = \Carbon\Carbon::createFromFormat('H:i', $request->login_time);
            $logoutTime = \Carbon\Carbon::createFromFormat('H:i', $request->logout_time);

            // Calculate total hours
            $totalHours = $logoutTime->diffInMinutes($loginTime) / 60;
            $data['total_hours'] = round($totalHours, 2);
        }

        $attendanceLog->update($data);

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance record updated successfully.');
    }

    public function destroyAttendance(AttendanceLog $attendanceLog)
    {
        $attendanceLog->delete();
        return redirect()->route('admin.attendance.index')->with('success', 'Attendance record deleted successfully.');
    }

    public function reports()
    {
        // Basic reports data
        $monthlyAttendance = AttendanceLog::selectRaw('MONTH(attendance_date) as month, COUNT(*) as count')
            ->whereYear('attendance_date', date('Y'))
            ->groupBy('month')
            ->get();

        $shiftDistribution = EmployeeShift::selectRaw('shift_id, COUNT(*) as count')
            ->with('shift')
            ->groupBy('shift_id')
            ->get();

        return view('admin.reports.index', compact('monthlyAttendance', 'shiftDistribution'));
    }

    public function settings()
    {
        return view('admin.settings.index');
    }
}
