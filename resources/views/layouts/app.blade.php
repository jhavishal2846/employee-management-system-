<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Employee Shift Management') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Nunito', sans-serif;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 70px;
            z-index: 1000;
            transition: all 0.3s;
        }
        .sidebar.collapsed {
            width: 70px;
        }
        .sidebar.collapsed .nav-link span {
            display: none;
        }
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            text-align: center;
            width: 100%;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            transition: all 0.3s;
            white-space: nowrap;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #28a745;
            background-color: rgba(40, 167, 69, 0.1);
        }
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
        }
        .main-content {
            margin-left: 250px;
            padding-top: 70px;
            min-height: 100vh;
            transition: margin-left 0.3s;
        }
        .main-content.expanded {
            margin-left: 70px;
        }
        .navbar {
            background-color: #ffffff !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            height: 70px;
        }
        .navbar-brand {
            color: #343a40 !important;
            font-weight: bold;
        }
        .dashboard-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
            padding: 1.5rem;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .dashboard-card .card-body {
            padding: 0;
        }
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .table {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 0;
        }
        .table thead th {
            background-color: #343a40;
            color: white;
            border: none;
            padding: 1rem;
        }
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }
        .table tbody tr:hover {
            background-color: #f1f3f4;
        }
        .stats-card {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border-radius: 10px;
            padding: 1.5rem;
        }
        .stats-card .card-body {
            text-align: center;
            padding: 0;
        }
        .stats-card h3 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .stats-card p {
            margin-bottom: 0;
            opacity: 0.8;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 250px;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }
            .overlay.show {
                display: block;
            }
        }
        .sidebar-toggle {
            background: none;
            border: none;
            color: #343a40;
            font-size: 1.2rem;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Mobile Overlay -->
        <div class="overlay d-md-none" id="sidebarOverlay"></div>

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top">
            <div class="container-fluid">
                <button class="sidebar-toggle d-none d-md-block me-2" id="sidebarToggle" type="button">
                    <i class="fas fa-bars"></i>
                </button>
                <button class="btn btn-outline-secondary d-md-none" type="button" id="mobileSidebarToggle" aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="navbar-brand ms-2" href="{{ url('/') }}">
                    Employee Shift Management
                </a>
                <div class="ms-auto">
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a></li>
                            </ul>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        @auth
        <!-- Sidebar -->
        <div class="sidebar d-none d-md-block" id="sidebarMenu">
            <nav class="nav flex-column">
                @if(Auth::user()->isAdmin())
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.employees*') ? 'active' : '' }}" href="{{ route('admin.employees.index') }}">
                        <i class="fas fa-users"></i> <span>Employees</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.shifts*') ? 'active' : '' }}" href="{{ route('admin.shifts.index') }}">
                        <i class="fas fa-clock"></i> <span>Shifts</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.attendance*') ? 'active' : '' }}" href="{{ route('admin.attendance.index') }}">
                        <i class="fas fa-calendar-check"></i> <span>Attendance</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                        <i class="fas fa-chart-bar"></i> <span>Reports</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                        <i class="fas fa-cog"></i> <span>Settings</span>
                    </a>
                @else
                    <a class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}" href="{{ route('employee.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('employee.shifts*') ? 'active' : '' }}" href="{{ route('employee.shifts.index') }}">
                        <i class="fas fa-clock"></i> <span>My Shifts</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('employee.attendance*') ? 'active' : '' }}" href="{{ route('employee.attendance.index') }}">
                        <i class="fas fa-calendar-check"></i> <span>Attendance</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('employee.requests*') ? 'active' : '' }}" href="{{ route('employee.requests.index') }}">
                        <i class="fas fa-exchange-alt"></i> <span>Shift Requests</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('employee.profile*') ? 'active' : '' }}" href="{{ route('employee.profile.edit') }}">
                        <i class="fas fa-user"></i> <span>Profile</span>
                    </a>
                @endif
            </nav>
        </div>
        @endauth

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            <div class="container-fluid py-4 px-4">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteMessage">Are you sure you want to delete this item?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global variables for delete functionality
        let deleteUrl = '';
        let csrfToken = '{{ csrf_token() }}';

        // Delete functions for different entities
        function deleteEmployee(id, name) {
            deleteUrl = '{{ route("admin.employees.index") }}/' + id;
            document.getElementById('deleteMessage').textContent = `Are you sure you want to delete employee "${name}"? This action cannot be undone.`;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        function deleteShift(id, name) {
            deleteUrl = '{{ route("admin.shifts.index") }}/' + id;
            document.getElementById('deleteMessage').textContent = `Are you sure you want to delete shift "${name}"? This action cannot be undone.`;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        function deleteAttendance(id, description) {
            deleteUrl = '{{ route("admin.attendance.index") }}/' + id;
            document.getElementById('deleteMessage').textContent = `Are you sure you want to delete attendance record "${description}"? This action cannot be undone.`;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        // Handle delete confirmation
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (deleteUrl) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                form.style.display = 'none';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        });

        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
            const sidebar = document.getElementById('sidebarMenu');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');

            // Desktop sidebar toggle (collapse/expand)
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                });
            }

            // Mobile sidebar toggle
            if (mobileSidebarToggle && sidebar && overlay) {
                mobileSidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                });

                // Close sidebar when clicking overlay
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }

            // Close mobile sidebar when clicking a nav link
            const navLinks = sidebar.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        sidebar.classList.remove('show');
                        overlay.classList.remove('show');
                    }
                });
            });
        });
    </script>
</body>
</html>
