@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary: #3b82f6;
        --primary-dark: #1e40af;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --secondary: #8b5cf6;
    }

    /* Page Header */
    .page-header {
        margin-bottom: 2rem;
        animation: slideDown 0.5s ease-out;
    }

    .page-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-title h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .page-title i {
        font-size: 1.5rem;
        color: var(--primary);
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Back Button */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        color: #374151;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9375rem;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        border-color: #9ca3af;
        background: #f9fafb;
        color: #1f2937;
        transform: translateX(-2px);
    }

    /* Stats Cards */
    .stats-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stats-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        text-align: center;
        transition: box-shadow 0.3s ease;
    }

    .stats-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .stats-card h3 {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .stats-card p {
        color: #6b7280;
        font-weight: 500;
        margin: 0;
        font-size: 0.9375rem;
    }

    /* Dashboard Card */
    .dashboard-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }

    .dashboard-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .dashboard-card .card-body {
        padding: 0;
    }

    /* Table Styles */
    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .custom-table thead th {
        background: #f9fafb;
        color: #374151;
        font-weight: 600;
        padding: 1rem 1.25rem;
        text-align: left;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #e5e7eb;
        white-space: nowrap;
    }

    .custom-table tbody td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        color: #1f2937;
        vertical-align: middle;
    }

    .custom-table tbody tr {
        transition: all 0.2s ease;
    }

    .custom-table tbody tr:hover {
        background: #f9fafb;
    }

    .custom-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Table Cell Styles */
    .attendance-date {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .attendance-date-main {
        font-weight: 500;
        color: #1f2937;
    }

    .attendance-date-day {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .time-display {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #374151;
        font-weight: 500;
    }

    .time-display i {
        color: var(--primary);
        font-size: 0.875rem;
    }

    .hours-display {
        font-weight: 600;
        color: #1f2937;
    }

    /* Badges */
    .badge-custom {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.875rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-info {
        background: #dbeafe;
        color: #1e40af;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
        color: #9ca3af;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state-text {
        font-size: 1.125rem;
        color: #6b7280;
        font-weight: 500;
    }

    .empty-state-subtext {
        font-size: 0.9375rem;
        color: #9ca3af;
        margin-top: 0.5rem;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        padding: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }

    /* Action Buttons */
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 600;
        border: 2px solid;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-action-danger {
        background: white;
        border-color: #fecaca;
        color: #dc2626;
    }

    .btn-action-danger:hover {
        background: #fef2f2;
        border-color: #dc2626;
        color: #991b1b;
        transform: translateY(-1px);
    }

    .btn-action-primary {
        background: white;
        border-color: #bfdbfe;
        color: #2563eb;
    }

    .btn-action-primary:hover {
        background: #eff6ff;
        border-color: #2563eb;
        color: #1e40af;
        transform: translateY(-1px);
    }

    .btn-action-warning {
        background: white;
        border-color: #fed7aa;
        color: #ea580c;
    }

    .btn-action-warning:hover {
        background: #fff7ed;
        border-color: #ea580c;
        color: #c2410c;
        transform: translateY(-1px);
    }

    .btn-action-secondary {
        background: #6b7280;
        border-color: #6b7280;
        color: white;
    }

    .btn-action-secondary:hover {
        background: #4b5563;
        border-color: #4b5563;
        color: white;
        transform: translateY(-1px);
    }

    /* Custom Popup Animations */
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header-content {
            flex-direction: column;
            align-items: stretch;
        }

        .page-title h2 {
            font-size: 1.5rem;
        }

        .btn-back {
            justify-content: center;
        }

        .stats-section {
            grid-template-columns: 1fr;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .custom-table {
            min-width: 800px;
        }

        .custom-table thead th,
        .custom-table tbody td {
            padding: 0.75rem 1rem;
        }

        #attendanceControls {
            flex-direction: column;
            align-items: stretch;
        }

        #attendanceControls button {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <i class="fas fa-calendar-check"></i>
            <h2>My Attendance</h2>
        </div>
        <a href="{{ route('employee.dashboard') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Dashboard</span>
        </a>
    </div>
</div>

<!-- Current Status Cards -->
<div class="stats-section">
    <div class="stats-card">
        <h3>{{ number_format($thisMonthHours, 1) }}</h3>
        <p>This Month Hours</p>
    </div>
    <div class="stats-card">
        <h3>{{ $attendanceLogs->where('status', 'present')->count() }}</h3>
        <p>Present Days</p>
    </div>
    <div class="stats-card">
        <h3>{{ $attendanceLogs->where('status', 'absent')->count() }}</h3>
        <p>Absent Days</p>
    </div>
    <div class="stats-card">
        <h3>{{ $attendanceLogs->where('is_overtime', true)->count() }}</h3>
        <p>Overtime Days</p>
    </div>
</div>

<!-- Clock In/Out and Break Controls -->
<div class="dashboard-card" style="margin-bottom: 2rem;">
    <div class="card-body" style="padding: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h4 style="margin: 0; color: #1f2937; font-weight: 600;">Today's Attendance</h4>
            <div id="currentTime" style="font-size: 0.875rem; color: #6b7280; font-weight: 500;"></div>
        </div>
        <div id="breakTimer" style="display: none; margin-bottom: 1rem; padding: 0.75rem; background: #fef3c7; border: 1px solid #f59e0b; border-radius: 6px; color: #92400e; font-weight: 500;">
            <i class="fas fa-coffee" style="margin-right: 0.5rem;"></i>
            <span id="breakTimeDisplay">00:00:00</span>
        </div>
        <div id="attendanceControls" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
            <!-- Controls will be populated by JavaScript -->
        </div>
        <div id="attendanceStatus" style="margin-top: 1rem; padding: 1rem; background: #f9fafb; border-radius: 8px; border-left: 4px solid var(--primary);">
            <!-- Status will be shown here -->
        </div>
    </div>
</div>

<div class="dashboard-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Login Time</th>
                        <th>Logout Time</th>
                        <th>Total Hours</th>
                        <th>Break Duration</th>
                        <th>Status</th>
                        <th>Overtime</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendanceLogs as $log)
                    <tr>
                        <td>
                            <div class="attendance-date">
                                <span class="attendance-date-main">{{ $log->attendance_date->format('M d, Y') }}</span>
                                <span class="attendance-date-day">{{ $log->attendance_date->format('l') }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="time-display">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>{{ $log->login_time ? $log->login_time->format('H:i') : 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="time-display">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>{{ $log->logout_time ? $log->logout_time->format('H:i') : 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="hours-display">{{ $log->total_hours ? number_format($log->total_hours, 2) : 'N/A' }}</span>
                        </td>
                        <td>{{ $log->break_duration_minutes ? $log->break_duration_minutes . ' min' : 'N/A' }}</td>
                        <td>
                            <span class="badge-custom badge-{{ $log->status === 'present' ? 'success' : ($log->status === 'absent' ? 'danger' : 'warning') }}">
                                {{ ucfirst($log->status) }}
                            </span>
                        </td>
                        <td>
                            @if($log->is_overtime)
                            <span class="badge-custom badge-info">{{ number_format($log->overtime_hours, 2) }}h</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">📅</div>
                                <div class="empty-state-text">No attendance records found</div>
                                <div class="empty-state-subtext">Your attendance logs will appear here</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attendanceLogs->hasPages())
        <div class="pagination-wrapper">
            {{ $attendanceLogs->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Break Modal -->
<div id="breakModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 90%; max-width: 400px; border-radius: 8px;">
        <span class="close" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        <h3 style="margin-top: 0; color: #1f2937;">Start Break</h3>
        <form id="breakForm">
            <div style="margin-bottom: 1rem;">
                <label for="breakType" style="display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 600;">Break Type:</label>
                <select id="breakType" name="break_type" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px;" required>
                    <option value="short">Short Break</option>
                    <option value="lunch">Lunch Break</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                <button type="button" class="btn-action btn-action-secondary" id="cancelBreak" style="background: #6b7280; border-color: #6b7280; color: white;">Cancel</button>
                <button type="submit" class="btn-action btn-action-primary">Start Break</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const controlsDiv = document.getElementById('attendanceControls');
    const statusDiv = document.getElementById('attendanceStatus');
    const breakModal = document.getElementById('breakModal');
    const breakForm = document.getElementById('breakForm');
    const breakType = document.getElementById('breakType');

    // Load current attendance status
    loadAttendanceStatus();

    // Update current time every second
    setInterval(updateCurrentTime, 1000);
    updateCurrentTime();

    function loadAttendanceStatus() {
        fetch('/employee/attendance/status', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            updateControls(data);
        })
        .catch(error => {
            console.error('Error loading status:', error);
        });
    }

    function updateControls(data) {
        let controlsHtml = '';
        let statusHtml = '';

        // Handle break timer visibility
        const breakTimer = document.getElementById('breakTimer');
        if (data.status === 'on_break' && data.break_start_timestamp) {
            breakTimer.style.display = 'block';
            startBreakTimer(data.break_start_timestamp);
        } else {
            breakTimer.style.display = 'none';
            stopBreakTimer();
        }

        if (data.status === 'not_clocked_in') {
            controlsHtml = '<button id="clockInBtn" class="btn-action btn-action-primary" style="background: #10b981; border-color: #10b981; color: white;"><i class="fas fa-sign-in-alt"></i> Clock In</button>';
            statusHtml = '<div style="display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-info-circle" style="color: #6b7280;"></i><span style="color: #6b7280; font-weight: 500;">Not clocked in today</span></div>';
        } else if (data.status === 'clocked_in') {
            controlsHtml = `
                <button id="startBreakBtn" class="btn-action btn-action-warning"><i class="fas fa-coffee"></i> Start Break</button>
                <button id="clockOutBtn" class="btn-action btn-action-danger"><i class="fas fa-sign-out-alt"></i> Clock Out</button>
            `;
            statusHtml = `<div style="display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-check-circle" style="color: #10b981;"></i><span style="color: #10b981; font-weight: 500;">Clocked in at ${data.login_time}</span></div>`;
        } else if (data.status === 'on_break') {
            controlsHtml = '<button id="endBreakBtn" class="btn-action btn-action-primary"><i class="fas fa-play"></i> End Break</button>';
            statusHtml = `<div style="display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-pause-circle" style="color: #f59e0b;"></i><span style="color: #f59e0b; font-weight: 500;">On break since ${data.break_start_time}</span></div>`;
        } else if (data.status === 'clocked_out') {
            statusHtml = `<div style="display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-check-circle" style="color: #6b7280;"></i><span style="color: #6b7280; font-weight: 500;">Clocked out at ${data.logout_time} (${data.total_hours} hours)</span></div>`;
        }

        controlsDiv.innerHTML = controlsHtml;
        statusDiv.innerHTML = statusHtml;

        // Attach event listeners
        attachEventListeners();
    }

    function updateCurrentTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });
        document.getElementById('currentTime').textContent = timeString;
    }

    function showSuccessPopup(message) {
        showPopup(message, 'success');
    }

    function showErrorPopup(message) {
        showPopup(message, 'error');
    }

    function showPopup(message, type) {
        // Remove existing popup
        const existingPopup = document.querySelector('.custom-popup');
        if (existingPopup) {
            existingPopup.remove();
        }

        // Create popup
        const popup = document.createElement('div');
        popup.className = `custom-popup ${type}`;
        popup.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#d1fae5' : '#fee2e2'};
            color: ${type === 'success' ? '#065f46' : '#991b1b'};
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 10000;
            font-weight: 500;
            max-width: 400px;
            border-left: 4px solid ${type === 'success' ? 'var(--success)' : 'var(--danger)'};
            animation: slideInRight 0.3s ease-out;
        `;

        popup.innerHTML = `
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(popup);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (popup.parentNode) {
                popup.style.animation = 'slideOutRight 0.3s ease-in';
                setTimeout(() => popup.remove(), 300);
            }
        }, 5000);
    }

    function showConfirmationPopup(message, onConfirm) {
        // Remove existing popup
        const existingPopup = document.querySelector('.confirmation-popup');
        if (existingPopup) {
            existingPopup.remove();
        }

        // Create confirmation popup
        const popup = document.createElement('div');
        popup.className = 'confirmation-popup';
        popup.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10001;
        `;

        popup.innerHTML = `
            <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); max-width: 400px; width: 90%; text-align: center;">
                <div style="margin-bottom: 1.5rem;">
                    <i class="fas fa-question-circle" style="font-size: 3rem; color: #f59e0b; margin-bottom: 1rem;"></i>
                    <h3 style="margin: 0 0 0.5rem 0; color: #1f2937; font-weight: 600;">Confirm Action</h3>
                    <p style="margin: 0; color: #6b7280;">${message}</p>
                </div>
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <button id="confirmBtn" class="btn-action btn-action-primary" style="background: #10b981; border-color: #10b981; color: white;">Yes, Proceed</button>
                    <button id="cancelBtn" class="btn-action btn-action-secondary">Cancel</button>
                </div>
            </div>
        `;

        document.body.appendChild(popup);

        // Event listeners
        document.getElementById('confirmBtn').addEventListener('click', function() {
            popup.remove();
            onConfirm();
        });

        document.getElementById('cancelBtn').addEventListener('click', function() {
            popup.remove();
        });

        // Close on background click
        popup.addEventListener('click', function(e) {
            if (e.target === popup) {
                popup.remove();
            }
        });
    }

    function attachEventListeners() {
        const clockInBtn = document.getElementById('clockInBtn');
        const clockOutBtn = document.getElementById('clockOutBtn');
        const startBreakBtn = document.getElementById('startBreakBtn');
        const endBreakBtn = document.getElementById('endBreakBtn');

        if (clockInBtn) {
            clockInBtn.addEventListener('click', handleClockIn);
        }
        if (clockOutBtn) {
            clockOutBtn.addEventListener('click', handleClockOut);
        }
        if (startBreakBtn) {
            startBreakBtn.addEventListener('click', handleStartBreak);
        }
        if (endBreakBtn) {
            endBreakBtn.addEventListener('click', handleEndBreak);
        }
    }

    function handleClockIn() {
        fetch('/employee/attendance/clock-in', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadAttendanceStatus();
                showSuccessPopup('Clocked in successfully!');
            } else {
                showErrorPopup(data.error || 'Failed to clock in');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorPopup('An error occurred while clocking in');
        });
    }

    function handleClockOut() {
        showConfirmationPopup('Are you sure you want to clock out?', function() {
            fetch('/employee/attendance/clock-out', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadAttendanceStatus();
                    showSuccessPopup(`Clocked out successfully! Total hours: ${data.total_hours}, Overtime: ${data.overtime_hours}h`);
                } else {
                    showErrorPopup(data.error || 'Failed to clock out');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorPopup('An error occurred while clocking out');
            });
        });
    }

    // Break modal is no longer used for start break

    function handleStartBreak() {
        fetch('/employee/attendance/start-break', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ break_type: 'short' }) // Default to short break
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadAttendanceStatus();
                showSuccessPopup('Break started successfully!');
            } else {
                showErrorPopup(data.error || 'Failed to start break');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorPopup('An error occurred while starting break');
        });
    }

    let breakTimerInterval;

    function startBreakTimer(breakStartTime) {
        stopBreakTimer(); // Clear any existing timer

        function updateBreakTime() {
            const now = new Date();
            const breakStart = new Date(breakStartTime);

            const diff = now - breakStart;
            const minutesElapsed = Math.floor(diff / (1000 * 60));
            const secondsElapsed = Math.floor((diff % (1000 * 60)) / 1000);

            const timeString = `${minutesElapsed.toString().padStart(2, '0')}:${secondsElapsed.toString().padStart(2, '0')}`;
            document.getElementById('breakTimeDisplay').textContent = timeString;
        }

        updateBreakTime(); // Update immediately
        breakTimerInterval = setInterval(updateBreakTime, 1000);
    }

    function stopBreakTimer() {
        if (breakTimerInterval) {
            clearInterval(breakTimerInterval);
            breakTimerInterval = null;
        }
    }

    function handleEndBreak() {
        showConfirmationPopup('Are you sure you want to end your break?', function() {
            fetch('/employee/attendance/end-break', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    stopBreakTimer();
                    loadAttendanceStatus();
                    showSuccessPopup(`Break ended successfully! Duration: ${data.break_duration} minutes`);
                } else {
                    showErrorPopup(data.error || 'Failed to end break');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorPopup('An error occurred while ending break');
            });
        });
    }
});
</script>
@endsection
