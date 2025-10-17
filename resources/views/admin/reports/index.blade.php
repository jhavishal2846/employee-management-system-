@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-chart-bar text-success"></i> Reports & Analytics</h2>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Monthly Attendance</h5>
                        <canvas id="monthlyAttendanceChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Shift Distribution</h5>
                        <canvas id="shiftDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-body">
                <h5 class="card-title mb-3">Report Filters</h5>
                <form class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Report Type</label>
                        <select class="form-select">
                            <option>Attendance Report</option>
                            <option>Shift Report</option>
                            <option>Payroll Report</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-search"></i> Generate Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Attendance Chart
    const monthlyCtx = document.getElementById('monthlyAttendanceChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Attendance Count',
                data: [65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Shift Distribution Chart
    const shiftCtx = document.getElementById('shiftDistributionChart').getContext('2d');
    new Chart(shiftCtx, {
        type: 'doughnut',
        data: {
            labels: ['Morning', 'Evening', 'Night', 'Custom'],
            datasets: [{
                data: [30, 25, 20, 25],
                backgroundColor: [
                    '#28a745',
                    '#20c997',
                    '#17a2b8',
                    '#6c757d'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>
@endsection
