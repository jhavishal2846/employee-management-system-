<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shift;
use App\Models\EmployeeShift;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalEmployees = User::employees()->active()->count();
        $totalShifts = Shift::active()->count();
        $pendingShiftRequests = EmployeeShift::whereIn('status', ['pending', 'assigned'])->count();
        $todayAttendance = AttendanceLog::where('attendance_date', today())->count();

        $recentAttendance = AttendanceLog::with(['employee', 'shift'])
            ->latest()
            ->take(10)
            ->get();

        $assignedShifts = EmployeeShift::with(['employee', 'shift'])
            ->where('status', 'assigned')
            ->latest()
            ->take(5)
            ->get();

        $rejectedShifts = EmployeeShift::with(['employee', 'shift'])
            ->where('status', 'rejected')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalEmployees',
            'totalShifts',
            'pendingShiftRequests',
            'todayAttendance',
            'recentAttendance',
            'assignedShifts',
            'rejectedShifts'
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
        // Force delete to trigger cascade deletion of related records
        $employee->forceDelete();
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

    public function assignEmployeeToShift(Request $request, Shift $shift)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'shift_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Check if employee is already assigned to this shift on the same date
        $existingAssignment = EmployeeShift::where('employee_id', $request->employee_id)
            ->where('shift_id', $shift->id)
            ->where('shift_date', $request->shift_date)
            ->first();

        if ($existingAssignment) {
            return redirect()->back()->with('error', 'Employee is already assigned to this shift on the selected date.');
        }

        EmployeeShift::create([
            'employee_id' => $request->employee_id,
            'shift_id' => $shift->id,
            'shift_date' => $request->shift_date,
            'status' => 'assigned',
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Employee assigned to shift successfully.');
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
        // Get current settings from config or database
        $settings = [
            'company_name' => config('app.name', 'Employee Shift Management'),
            'default_working_hours' => config('app.default_working_hours', 8),
            'timezone' => config('app.timezone', 'UTC'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'default_working_hours' => 'required|integer|min:1|max:24',
            'timezone' => 'required|string|timezone',
        ]);

        // Update .env file for persistence
        $this->updateEnvironmentFile([
            'APP_NAME' => $request->company_name,
            'APP_DEFAULT_WORKING_HOURS' => $request->default_working_hours,
            'APP_TIMEZONE' => $request->timezone,
        ]);

        // Update config values for immediate effect
        config(['app.name' => $request->company_name]);
        config(['app.default_working_hours' => $request->default_working_hours]);
        config(['app.timezone' => $request->timezone]);

        return redirect()->back()->with('success', 'Settings updated successfully. Please run "php artisan config:cache" in your terminal to apply changes globally.');
    }

    public function backupDatabase()
    {
        try {
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $path = storage_path('backups/' . $filename);

            // Ensure backup directory exists
            if (!file_exists(storage_path('backups'))) {
                mkdir(storage_path('backups'), 0755, true);
            }

            // Use mysqldump command (adjust for your database)
            $command = sprintf(
                'mysqldump -u%s -p%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                $path
            );

            exec($command, $output, $returnVar);

            if ($returnVar === 0) {
                return response()->download($path)->deleteFileAfterSend();
            } else {
                return redirect()->back()->with('error', 'Database backup failed.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Database backup failed: ' . $e->getMessage());
        }
    }

    public function exportData()
    {
        try {
            $data = [
                'employees' => User::employees()->get(),
                'shifts' => Shift::all(),
                'attendance_logs' => AttendanceLog::all(),
                'employee_shifts' => EmployeeShift::all(),
            ];

            $filename = 'export_' . date('Y-m-d_H-i-s') . '.json';
            $path = storage_path('exports/' . $filename);

            // Ensure export directory exists
            if (!file_exists(storage_path('exports'))) {
                mkdir(storage_path('exports'), 0755, true);
            }

            file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));

            return response()->download($path)->deleteFileAfterSend();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data export failed: ' . $e->getMessage());
        }
    }

    public function clearLogs()
    {
        try {
            // Clear Laravel logs
            $logPath = storage_path('logs/laravel.log');
            if (file_exists($logPath)) {
                file_put_contents($logPath, '');
            }

            // Clear audit logs from database
            DB::table('audit_logs')->delete();

            return redirect()->back()->with('success', 'Logs cleared successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to clear logs: ' . $e->getMessage());
        }
    }

    private function updateEnvironmentFile(array $data)
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            // Escape special characters in value
            $escapedValue = $this->escapeEnvValue($value);
            $pattern = "/^{$key}=.*$/m";
            $replacement = "{$key}={$escapedValue}";
            $envContent = preg_replace($pattern, $replacement, $envContent);
        }

        file_put_contents($envFile, $envContent);
    }

    private function escapeEnvValue($value)
    {
        // If value contains spaces or special characters, wrap in quotes
        if (preg_match('/\s/', $value) || strpos($value, '"') !== false) {
            return '"' . addslashes($value) . '"';
        }
        return $value;
    }
}
