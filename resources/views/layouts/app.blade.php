<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Perhitungan Gaji Borongan')
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            color: #1f2937;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 240px;
            height: 100vh;
            background: linear-gradient(180deg, #1e40af 0%, #1d4ed8 100%);
            color: white;
            padding: 25px 15px;
            border-right: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 1px 0 0 rgba(30, 64, 175, 0.18);
        }

        .sidebar-title {
            font-size: 20px;
            font-weight: bold;
            padding: 0 15px;
            margin-bottom: 30px;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .nav-menu a {
            color: rgba(255, 255, 255, 0.92);
            text-decoration: none;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.05);
            transition: all 0.2s ease;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            border-color: rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.2);
        }

        .logout-form {
            position: absolute;
            left: 15px;
            right: 15px;
            bottom: 25px;
        }

        .logout-button {
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.08);
            color: white;
            padding: 12px 14px;
            text-align: left;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .logout-button:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-color: rgba(37, 99, 235, 0.5);
            color: white;
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.2);
        }

        .main {
            margin-left: 240px;
            min-height: 100vh;
        }

        .topbar {
            height: 70px;
            background: white;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
        }

        .topbar-title {
            font-size: 18px;
            font-weight: bold;
        }

        .admin-name {
            font-size: 14px;
            color: #4b5563;
        }

        .content {
            padding: 30px;
        }

        .page-title {
            margin: 0;
            font-size: 26px;
            color: #111827;
        }

        .page-description {
            margin-top: 7px;
            color: #6b7280;
            font-size: 14px;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 25px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 22px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .stat-title {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 25px;
            font-weight: bold;
            color: #111827;
        }

        .btn {
            display: inline-block;
            border: none;
            border-radius: 6px;
            padding: 9px 15px;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.18);
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        table th {
            background: #f9fafb;
            color: #374151;
            font-weight: 600;
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
            white-space: nowrap;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
            white-space: nowrap;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 7px;
            color: #374151;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: white;
            font-size: 14px;
        }

        .alert {
            padding: 13px 16px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        @media (max-width: 900px) {
            .sidebar {
                width: 200px;
            }

            .main {
                margin-left: 200px;
            }

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .logout-form {
                position: static;
                margin-top: 20px;
            }

            .main {
                margin-left: 0;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .content {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <aside class="sidebar">

        <div class="sidebar-title">
            Perhitungan Gaji
        </div>

        <nav class="nav-menu">

            <a
                href="{{ route('dashboard') }}"
                class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
            >
                Dashboard
            </a>

            <a
                href="{{ route('checklogs.index') }}"
                class="{{ request()->routeIs('checklogs.*') ? 'active' : '' }}"
            >
                Hitung Gaji
            </a>

            <a
                href="{{ route('employees.index') }}"
                class="{{ request()->routeIs('employees.*') ? 'active' : '' }}"
            >
                Data Karyawan
            </a>

            <a
                href="{{ route('salary_archives.index') }}"
                class="{{ request()->routeIs('salary_archives.*') ? 'active' : '' }}"
            >
                Arsip Gaji
            </a>

        </nav>

        <form
            method="POST"
            action="{{ route('logout') }}"
            class="logout-form"
        >
            @csrf

            <button type="submit" class="logout-button">
                Logout
            </button>
        </form>

    </aside>


    <div class="main">

        <header class="topbar">

            <div class="topbar-title">
                @yield('title', 'Dashboard')
            </div>

            <div class="admin-name">
                {{ Auth::user()->name ?? 'Admin' }}
            </div>

        </header>


        <main class="content">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')

        </main>

    </div>

</body>
</html>