@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary: #3b82f6;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #06b6d4;
    }

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
        color: var(--success);
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .report-tabs {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .report-tabs .nav-tabs {
        border: none;
        padding: 0 1.5rem;
        background: #f9fafb;
    }

    .report-tabs .nav-tabs .nav-link {
        border: none;
        color: #6b7280;
        font-weight: 600;
        padding: 1rem 1.5rem;
        position: relative;
    }

    .report-tabs .nav-tabs .nav-link.active {
        color: var(--primary);
        background: white;
        border-bottom: 3px solid var(--primary);
    }

    .report-tabs .nav-tabs .nav-link:hover {
        color: var(--primary);
        background: rgba(59, 130, 246, 0.05);
    }

    .dashboard-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .dashboard-card .card-header {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        padding: 1.25rem 1.5rem;
    }

    .dashboard-card .card-header h5 {
        margin: 0;
        font-weight: 600;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .dashboard-card .card-body {
        padding: 1.5rem;
    }

    .filters-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .filters-section .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .filters-section .form-select,
    .filters-section .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.75rem;
        font-size: 0.875rem;
    }

    .filters-section .form-select:focus,
    .filters-section .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn-generate {
        background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        font-size: 0.9375rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3);
    }

    .btn-generate:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 12px -1px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .btn-export {
        background: linear-gradient(135deg, var(--primary) 0%, #2563eb 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        font-size: 0.9375rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        margin-left: 1rem;
    }

    .btn-export:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 12px -1px rgba(59, 130, 246, 0.4);
        color: white;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        border-left: 4px solid var(--primary);
        transition: transform 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px 0 rgba(0, 0, 0, 0.15);
    }

    .stat-card.success { border-left-color: var(--success); }
    .stat-card.warning { border-left-color: var(--warning); }
    .stat-card.danger { border-left-color: var(--danger); }
    .stat-card.info { border-left-color: var(--info); }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }

    .chart-container {
        position: relative;
        height: 300px;
        margin-bottom: 2rem;
    }

    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

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
    }

    .custom-table tbody td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        color: #1f2937;
        vertical-align: middle;
    }

    .custom-table tbody tr:hover {
        background: #f9fafb;
    }

    .badge-custom {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-success { background: rgba(16, 185, 129, 0.1); color: var(--success); }
    .badge-warning { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
    .badge-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
    .badge-info { background: rgba(6, 182, 212, 0.1); color: var(--info); }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6b7280;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .empty-state-text {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state-subtext {
        font-size: 0.875rem;
    }

    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @media (max-width: 768px) {
        .page-header-content {
            flex-direction: column;
            align-items: stretch;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filters-section .row > div {
            margin-bottom: 1rem;
        }
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <i class="fas fa-chart-bar"></i>
            <h2>Reports & Analytics</h2>
        </div>
        <div>
            <button type="button" class="btn-export" onclick="exportReport()">
                <i class="fas fa-download"></i> Export Report
            </button>
        </div>
    </div>
</div>

<!-- Report Type Tabs -->
<div class="report-tabs">
    <ul class="nav nav-tabs" id="reportTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $reportType == 'attendance' ? 'active' : '' }}" id="attendance-tab" data-bs-toggle="tab" data-bs-target="#attendance" type="button" role="tab">
                <i class="fas fa-calendar-check"></i> Attendance
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $reportType == 'payroll' ? 'active' : '' }}" id="payroll-tab" data-bs-toggle="tab" data-bs-target="#payroll" type="button" role="tab">
                <i class="fas fa-money-bill-wave"></i> Payroll
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $reportType == 'shifts' ? 'active' : '' }}" id="shifts-tab" data-bs-toggle="tab" data-bs-target="#shifts" type="button" role="tab">
                <i class="fas fa-clock"></i> Shifts
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $reportType == 'performance' ? 'active' : '' }}" id="performance-tab" data-bs-toggle="tab" data-bs-target="#performance" type="button" role="tab">
                <i class="fas fa-trophy"></i> Performance
            </button>
        </li>
    </ul>
</div>

<!-- Filters Section -->
<div class="filters-section">
    <form method="GET" action="{{ route('admin.reports.index') }}" id="reportForm">
        <input type="hidden" name="type" value="{{ $reportType }}" id="reportTypeInput">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Employee (Optional)</label>
                <select name="employee_id" class="form-select">
                    <option value="">All Employees</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ $employeeId == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }} ({{ $employee->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-generate w-100">
                    <i class="fas fa-search"></i> Generate
                </button>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary" onclick="resetFilters()">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="printReport()">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Tab Content -->
<div class="tab-content" id="reportTabContent">
    <!-- Attendance Report -->
    <div class="tab-pane fade {{ $reportType == 'attendance' ? 'show active' : '' }}" id="attendance" role="tabpanel">
        @if(isset($attendanceLogs))
            <!-- Attendance Stats -->
            <div class="stats-grid">
                <div class="stat-card success">
                    <div class="stat-value">{{ $employeeSummary->sum('total_days') ?? 0 }}</div>
                    <div class="stat-label">Total Days</div>
                </div>
                <div class="stat-card info">
                    <div class="stat-value">{{ $employeeSummary->sum('present_days') ?? 0 }}</div>
                    <div class="stat-label">Present Days</div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-value">{{ $employeeSummary->sum('late_days') ?? 0 }}</div>
                    <div class="stat-label">Late Days</div>
                </div>
                <div class="stat-card danger">
                    <div class="stat-value">{{ $employeeSummary->sum('absent_days') ?? 0 }}</div>
                    <div class="stat-label">Absent Days</div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h5><i class="fas fa-chart-line"></i> Monthly Attendance Trend</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="monthlyAttendanceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h5><i class="fas fa-chart-pie"></i> Status Distribution</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="statusDistributionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Summary Table -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h5><i class="fas fa-users"></i> Employee Attendance Summary</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Total Days</th>
                                    <th>Present</th>
                                    <th>Late</th>
                                    <th>Absent</th>
                                    <th>Total Hours</th>
                                    <th>Attendance Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employeeSummary ?? [] as $summary)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="employee-avatar me-2">
                                                {{ strtoupper(substr($summary['employee']->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $summary['employee']->name }}</div>
                                                <small class="text-muted">{{ $summary['employee']->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $summary['total_days'] }}</td>
                                    <td><span class="badge-custom badge-success">{{ $summary['present_days'] }}</span></td>
                                    <td><span class="badge-custom badge-warning">{{ $summary['late_days'] }}</span></td>
                                    <td><span class="badge-custom badge-danger">{{ $summary['absent_days'] }}</span></td>
                                    <td>{{ number_format($summary['total_hours'], 2) }}</td>
                                    <td>
                                        <span class="badge-custom {{ $summary['attendance_rate'] >= 80 ? 'badge-success' : ($summary['attendance_rate'] >= 60 ? 'badge-warning' : 'badge-danger') }}">
                                            {{ $summary['attendance_rate'] }}%
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">📊</div>
                                            <div class="empty-state-text">No attendance data found</div>
                                            <div class="empty-state-subtext">Try adjusting your date range or filters</div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Payroll Report -->
    <div class="tab-pane fade {{ $reportType == 'payroll' ? 'show active' : '' }}" id="payroll" role="tabpanel">
        @if(isset($payrollReports))
            <!-- Payroll Stats -->
            <div class="stats-grid">
                <div class="stat-card success">
                    <div class="stat-value">{{ $payrollSummary['total_reports'] ?? 0 }}</div>
                    <div class="stat-label">Total Reports</div>
                </div>
                <div class="stat-card info">
                    <div class="stat-value">${{ number_format($payrollSummary['total_payroll'] ?? 0, 2) }}</div>
                    <div class="stat-label">Total Payroll</div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-value">${{ number_format($payrollSummary['average_payroll'] ?? 0, 2) }}</div>
                    <div class="stat-label">Average Payroll</div>
                </div>
                <div class="stat-card danger">
                    <div class="stat-value">{{ $payrollSummary['pending_reports'] ?? 0 }}</div>
                    <div class="stat-label">Pending Payments</div>
                </div>
            </div>

            <!-- Payroll Chart -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h5><i class="fas fa-chart-bar"></i> Monthly Payroll Trend</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="monthlyPayrollChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payroll Reports Table -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h5><i class="fas fa-file-invoice-dollar"></i> Payroll Reports</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Period</th>
                                    <th>Total Hours</th>
                                    <th>Regular Pay</th>
                                    <th>Overtime Pay</th>
                                    <th>Total Pay</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payrollReports ?? [] as $report)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="employee-avatar me-2">
                                                {{ strtoupper(substr($report->employee->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $report->employee->name }}</div>
                                                <small class="text-muted">{{ $report->employee->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $report->period_start->format('M d') }} - {{ $report->period_end->format('M d, Y') }}</td>
                                    <td>{{ number_format($report->total_hours, 2) }}</td>
                                    <td>${{ number_format($report->regular_pay, 2) }}</td>
                                    <td>${{ number_format($report->overtime_pay, 2) }}</td>
                                    <td><strong>${{ number_format($report->total_pay, 2) }}</strong></td>
                                    <td>
                                        <span class="badge-custom {{ $report->payment_status == 'paid' ? 'badge-success' : 'badge-warning' }}">
                                            {{ ucfirst($report->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">💰</div>
                                            <div class="empty-state-text">No payroll reports found</div>
                                            <div class="empty-state-subtext">Try adjusting your date range or filters</div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Shifts Report -->
    <div class="tab-pane fade {{ $reportType == 'shifts' ? 'show active' : '' }}" id="shifts" role="tabpanel">
        @if(isset($employeeShifts))
            <!-- Shift Stats -->
            <div class="stats-grid">
                <div class="stat-card success">
                    <div class="stat-value">{{ $employeeShiftSummary->sum('total_shifts') ?? 0 }}</div>
                    <div class="stat-label">Total Shifts</div>
                </div>
                <div class="stat-card info">
                    <div class="stat-value">{{ $employeeShiftSummary->sum('accepted_shifts') ?? 0 }}</div>
                    <div class="stat-label">Accepted Shifts</div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-value">{{ $employeeShiftSummary->sum('pending_shifts') ?? 0 }}</div>
                    <div class="stat-label">Pending Shifts</div>
                </div>
                <div class="stat-card danger">
                    <div class="stat-value">{{ $employeeShiftSummary->sum('rejected_shifts') ?? 0 }}</div>
                    <div class="stat-label">Rejected Shifts</div>
                </div>
            </div>

            <!-- Shift Charts -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h5><i class="fas fa-chart-pie"></i> Shift Distribution</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="shiftDistributionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h5><i class="fas fa-chart-donut"></i> Shift Status Distribution</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="shiftStatusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shift Summary Table -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h5><i class="fas fa-clock"></i> Employee Shift Summary</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Total Shifts</th>
                                    <th>Accepted</th>
                                    <th>Pending</th>
                                    <th>Rejected</th>
                                    <th>Acceptance Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employeeShiftSummary ?? [] as $summary)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="employee-avatar me-2">
                                                {{ strtoupper(substr($summary['employee']->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $summary['employee']->name }}</div>
                                                <small class="text-muted">{{ $summary['employee']->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $summary['total_shifts'] }}</td>
                                    <td><span class="badge-custom badge-success">{{ $summary['accepted_shifts'] }}</span></td>
                                    <td><span class="badge-custom badge-warning">{{ $summary['pending_shifts'] }}</span></td>
                                    <td><span class="badge-custom badge-danger">{{ $summary['rejected_shifts'] }}</span></td>
                                    <td>
                                        @php
                                            $acceptanceRate = $summary['total_shifts'] > 0 ? round(($summary['accepted_shifts'] / $summary['total_shifts']) * 100, 1) : 0;
                                        @endphp
                                        <span class="badge-custom {{ $acceptanceRate >= 80 ? 'badge-success' : ($acceptanceRate >= 60 ? 'badge-warning' : 'badge-danger') }}">
                                            {{ $acceptanceRate }}%
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">⏰</div>
                                            <div class="empty-state-text">No shift data found</div>
                                            <div class="empty-state-subtext">Try adjusting your date range or filters</div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Performance Report -->
    <div class="tab-pane fade {{ $reportType == 'performance' ? 'show active' : '' }}" id="performance" role="tabpanel">
        @if(isset($performanceMetrics))
            <!-- Performance Stats -->
            <div class="stats-grid">
                <div class="stat-card success">
                    <div class="stat-value">{{ round($performanceMetrics->avg('attendance_rate'), 1) }}%</div>
                    <div class="stat-label">Avg Attendance Rate</div>
                </div>
                <div class="stat-card info">
                    <div class="stat-value">{{ round($performanceMetrics->avg('punctuality_rate'), 1) }}%</div>
                    <div class="stat-label">Avg Punctuality Rate</div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-value">{{ number_format($performanceMetrics->avg('total_hours'), 1) }}</div>
                    <div class="stat-label">Avg Hours/Day</div>
                </div>
                <div class="stat-card danger">
                    <div class="stat-value">{{ $performanceMetrics->sum('absent_days') }}</div>
                    <div class="stat-label">Total Absences</div>
                </div>
            </div>

            <!-- Top Performers -->
            <div class="dashboard-card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-trophy"></i> Top Performers</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($topPerformers ?? [] as $index => $performer)
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="me-3">
                                    <span class="badge bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'bronze') }} rounded-circle p-2">
                                        {{ $index + 1 }}
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">{{ $performer['employee']->name }}</div>
                                    <small class="text-muted">{{ $performer['attendance_rate'] }}% attendance rate</small>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="empty-state">
                                <div class="empty-state-icon">🏆</div>
                                <div class="empty-state-text">No performance data available</div>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Department Performance -->
            <div class="dashboard-card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-building"></i> Department Performance</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Total Employees</th>
                                    <th>Avg Attendance Rate</th>
                                    <th>Avg Punctuality Rate</th>
                                    <th>Total Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($departmentPerformance ?? [] as $dept => $performance)
                                <tr>
                                    <td><strong>{{ $dept ?: 'No Department' }}</strong></td>
                                    <td>{{ $performance['total_employees'] }}</td>
                                    <td>{{ $performance['average_attendance_rate'] }}%</td>
                                    <td>{{ $performance['average_punctuality_rate'] }}%</td>
                                    <td>{{ number_format($performance['total_hours'], 1) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">🏢</div>
                                            <div class="empty-state-text">No department data found</div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Detailed Performance Table -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-line"></i> Detailed Performance Metrics</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Attendance Rate</th>
                                    <th>Punctuality Rate</th>
                                    <th>Total Hours</th>
                                    <th>Avg Hours/Day</th>
                                    <th>Overtime Hours</th>
                                    <th>Absences</th>
                                    <th>Late Days</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($performanceMetrics ?? [] as $metric)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="employee-avatar me-2">
                                                {{ strtoupper(substr($metric['employee']->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $metric['employee']->name }}</div>
                                                <small class="text-muted">{{ $metric['employee']->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-custom {{ $metric['attendance_rate'] >= 80 ? 'badge-success' : ($metric['attendance_rate'] >= 60 ? 'badge-warning' : 'badge-danger') }}">
                                            {{ $metric['attendance_rate'] }}%
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge-custom {{ $metric['punctuality_rate'] >= 80 ? 'badge-success' : ($metric['punctuality_rate'] >= 60 ? 'badge-warning' : 'badge-danger') }}">
                                            {{ $metric['punctuality_rate'] }}%
                                        </span>
                                    </td>
                                    <td>{{ number_format($metric['total_hours'], 2) }}</td>
                                    <td>{{ number_format($metric['average_hours_per_day'], 2) }}</td>
                                    <td>{{ number_format($metric['overtime_hours'], 2) }}</td>
                                    <td><span class="badge-custom badge-danger">{{ $metric['absent_days'] }}</span></td>
                                    <td><span class="badge-custom badge-warning">{{ $metric['late_days'] }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">📈</div>
                                            <div class="empty-state-text">No performance data found</div>
                                            <div class="empty-state-subtext">Try adjusting your date range or filters</div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabs = document.querySelectorAll('#reportTabs .nav-link');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const target = this.getAttribute('data-bs-target').substring(1);
            document.getElementById('reportTypeInput').value = target;
        });
    });

    // Initialize charts based on active tab
    initializeCharts();

    function initializeCharts() {
        const activeTab = document.querySelector('#reportTabs .nav-link.active');
        if (!activeTab) return;

        const tabId = activeTab.getAttribute('data-bs-target').substring(1);

        switch(tabId) {
            case 'attendance':
                initializeAttendanceCharts();
                break;
            case 'payroll':
                initializePayrollCharts();
                break;
            case 'shifts':
                initializeShiftCharts();
                break;
        }
    }

    function initializeAttendanceCharts() {
        // Monthly Attendance Chart
        const monthlyCtx = document.getElementById('monthlyAttendanceChart');
        if (monthlyCtx) {
            const monthlyData = @json($monthlyAttendance ?? []);
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: Object.keys(monthlyData).map(month => {
                        const date = new Date(
