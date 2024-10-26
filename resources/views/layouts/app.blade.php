<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SecurityPrime') - Panel de Control</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #818cf8;
            --bg-color: #f0f4f8;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --navbar-height: 60px;
            --card-proyecto: #FF6B6B;
            --card-cliente: #4ECDC4;
            --card-tarea: #45B7D1;
            --card-empleado: #FFA07A;
            --card-equipo: #96CEB4;
            --card-recurso: #FFEEAD;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-color);
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        /* Navbar Styles */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 0.5rem 1rem;
            height: var(--navbar-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }

        .navbar .nav-link {
            font-weight: 600;
            color: white !important;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .navbar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .navbar .dropdown-menu {
            background: white;
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-top: 0.5rem;
        }

        .navbar .dropdown-item {
            padding: 0.5rem 1rem;
            font-weight: 500;
            color: #333;
            transition: all 0.3s ease;
        }

        .navbar .dropdown-item:hover {
            background: #f0f0f0;
            color: var(--primary-color);
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            height: calc(100vh - var(--navbar-height));
            width: var(--sidebar-width);
            background: white;
            transition: all 0.3s ease;
            z-index: 999;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--primary-color) transparent;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: var(--primary-color);
            border-radius: 20px;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .menu-item {
            padding: 1rem;
            display: flex;
            align-items: center;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            margin: 0.5rem;
            font-weight: 600;
        }

        .menu-item:hover {
            background: #f0f0f0;
            color: var(--primary-color);
            text-decoration: none;
        }

        .menu-item.active {
            background: var(--primary-color);
            color: white;
        }

        .menu-item i {
            font-size: 1.2rem;
            margin-right: 1rem;
            width: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .menu-item.active i {
            color: white;
        }

        .sidebar.collapsed .menu-item span {
            display: none;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            padding-top: calc(var(--navbar-height) + 2rem);
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Card Styles */
        .dashboard-card {
            border-radius: 1rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            overflow: hidden;
            position: relative;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .card-content {
            padding: 1.5rem;
            color: white;
        }

        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .card-value {
            font-size: 2rem;
            font-weight: 700;
        }

        /* Cards Colors */
        .card-proyecto { background: var(--card-proyecto); }
        .card-cliente { background: var(--card-cliente); }
        .card-tarea { background: var(--card-tarea); }
        .card-empleado { background: var(--card-empleado); }
        .card-equipo { background: var(--card-equipo); }
        .card-recurso { background: var(--card-recurso); }

        /* Table Styles */
        .custom-table {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .custom-table thead th {
            background: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 1rem;
            border: none;
        }

        .custom-table tbody td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }

        .alert-dismissible .btn-close {
            padding: 1rem;
        }

        /* Button Styles */
        .btn-custom {
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-custom-primary {
            background: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-custom-primary:hover {
            background: var(--secondary-color);
            color: white;
        }

        /* Form Styles */
        .form-control {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        /* Badge Styles */
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
        }

        /* Sidebar Toggle Button */
        #sidebarToggle {
            position: fixed;
            left: calc(var(--sidebar-width) - 20px);
            top: calc(var(--navbar-height) + 10px);
            background: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1001;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        #sidebarToggle:hover {
            background: #f0f0f0;
        }

        #sidebarToggle.collapsed {
            left: calc(var(--sidebar-collapsed-width) - 20px);
        }

        /* Breadcrumb Styles */
        .custom-breadcrumb {
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb-item.active {
            font-weight: 600;
        }

        /* Recent Projects Section */
        .recent-projects {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
            overflow: hidden;
        }

        .recent-projects h3 {
            padding: 1rem;
            background: var(--primary-color);
            color: white;
            margin: 0;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                width: var(--sidebar-collapsed-width);
            }

            .main-content {
                margin-left: var(--sidebar-collapsed-width);
            }

            #sidebarToggle {
                left: calc(var(--sidebar-collapsed-width) - 20px);
            }

            .menu-item span {
                display: none;
            }

            .dashboard-card {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 1rem;
                padding-top: calc(var(--navbar-height) + 1rem);
            }

            .navbar-brand {
                font-size: 1.2rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    @include('layouts.partials.navbar')

    <!-- Sidebar -->
    @include('layouts.partials.sidebar')

    <!-- Botón Toggle Sidebar -->
    <button id="sidebarToggle">
        <i class="fas fa-chevron-left"></i>
    </button>

    <!-- Contenido Principal -->
    <div class="main-content" id="mainContent">
        <!-- Alertas -->
        @include('layouts.partials.alerts')
        
        <!-- Contenido -->
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Toggle del Sidebar
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const sidebarToggle = document.getElementById('sidebarToggle');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                sidebarToggle.classList.toggle('collapsed');
            });
        }

        // Cerrar alertas automáticamente
        document.querySelectorAll('.alert-dismissible').forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // Activar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Marcar ítem activo en el menú
        document.querySelectorAll('.menu-item').forEach(item => {
            if (item.href === window.location.href) {
                item.classList.add('active');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>