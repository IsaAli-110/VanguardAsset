<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VanguardAsset - Sistem Manajemen Aset & RBAC</title>
    
    <!-- Google Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome for Premium Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-deep: #090d16;
            --bg-main: #0f172a;
            --bg-card: rgba(30, 41, 59, 0.55);
            --bg-card-hover: rgba(30, 41, 59, 0.85);
            --border-glow: rgba(99, 102, 241, 0.15);
            --border-dim: rgba(255, 255, 255, 0.06);
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.35);
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            --accent-gradient: linear-gradient(135deg, #ec4899 0%, #d946ef 100%);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --radius-lg: 16px;
            --radius-md: 10px;
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            scrollbar-width: thin;
            scrollbar-color: rgba(99, 102, 241, 0.3) transparent;
        }

        /* Custom Scrollbar (Webkit) */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(99,102,241,0.4), rgba(192,132,252,0.4));
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, rgba(99,102,241,0.6), rgba(192,132,252,0.6));
        }

        /* Scroll Progress Bar */
        .scroll-progress {
            position: fixed;
            top: 0; left: 0;
            height: 3px;
            background: linear-gradient(90deg, #6366f1, #c084fc, #ec4899);
            z-index: 9999;
            transition: width 0.1s linear;
            width: 0%;
        }

        body {
            background-color: var(--bg-deep);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            position: relative;
        }

        /* Animated Gradient Background Blobs */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            z-index: -1;
            pointer-events: none;
        }
        body::before {
            width: 600px; height: 600px;
            background: radial-gradient(circle, #6366f1 0%, transparent 70%);
            top: -100px; left: -100px;
            animation: blobFloat1 20s ease-in-out infinite;
        }
        body::after {
            width: 500px; height: 500px;
            background: radial-gradient(circle, #ec4899 0%, transparent 70%);
            bottom: -100px; right: -100px;
            animation: blobFloat2 25s ease-in-out infinite;
        }
        @keyframes blobFloat1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(80px, 60px) scale(1.1); }
            50% { transform: translate(40px, 120px) scale(0.95); }
            75% { transform: translate(-30px, 80px) scale(1.05); }
        }
        @keyframes blobFloat2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(-60px, -80px) scale(1.1); }
            66% { transform: translate(50px, -40px) scale(0.9); }
        }

        /* Page Transition */
        .container {
            animation: pageEnter 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes pageEnter {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Navbar */
        .navbar {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-dim);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--text-main);
            letter-spacing: -0.5px;
        }

        .navbar-brand span {
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .navbar-brand i {
            color: var(--primary);
            text-shadow: 0 0 15px var(--primary-glow);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .nav-link {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition-smooth);
            padding: 0.5rem 0.75rem;
            border-radius: var(--radius-md);
            white-space: nowrap;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.05);
        }

        /* Nav Badge (notification count) */
        .nav-badge {
            background: var(--danger); color: #fff;
            padding: 1px 5px; border-radius: 50%;
            font-size: 0.65rem; font-weight: 700;
            min-width: 16px; text-align: center; line-height: 1.4;
        }
        .nav-badge.warn { background: var(--warning); color: #000; }

        /* Dropdown */
        .nav-dropdown {
            position: relative;
        }
        .nav-dropdown-trigger {
            color: var(--text-muted);
            background: none; border: none;
            font-size: 0.9rem; font-weight: 500;
            display: flex; align-items: center; gap: 0.5rem;
            transition: var(--transition-smooth);
            padding: 0.5rem 0.75rem;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            white-space: nowrap;
        }
        .nav-dropdown-trigger:hover {
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.05);
        }
        .nav-dropdown-trigger .chevron {
            font-size: 0.6rem; transition: transform 0.2s ease; margin-left: 2px;
        }
        .nav-dropdown.open .nav-dropdown-trigger .chevron {
            transform: rotate(180deg);
        }
        .nav-dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            min-width: 200px;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-dim);
            border-radius: var(--radius-md);
            padding: 0.35rem;
            z-index: 2000;
            box-shadow: 0 12px 40px -8px rgba(0,0,0,0.6);
            animation: dropdownFade 0.15s ease-out;
        }
        .nav-dropdown.open .nav-dropdown-menu {
            display: block;
        }
        .nav-dropdown-item {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.6rem 0.85rem;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.88rem; font-weight: 500;
            border-radius: 8px;
            transition: var(--transition-smooth);
        }
        .nav-dropdown-item:hover {
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.06);
        }
        .nav-dropdown-item i { width: 18px; text-align: center; font-size: 0.85rem; }
        .nav-dropdown-divider {
            height: 1px; background: var(--border-dim); margin: 0.25rem 0.5rem;
        }
        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Profile Dropdown */
        .profile-trigger {
            display: flex; align-items: center; gap: 0.6rem;
            background: rgba(99, 102, 241, 0.08);
            border: 1px solid rgba(99, 102, 241, 0.15);
            padding: 0.35rem 0.7rem 0.35rem 0.35rem;
            border-radius: 28px;
            cursor: pointer;
            transition: var(--transition-smooth);
            font-family: 'Inter', sans-serif;
        }
        .profile-trigger:hover {
            background: rgba(99, 102, 241, 0.15);
            border-color: rgba(99, 102, 241, 0.3);
        }
        .profile-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: var(--primary-gradient);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.8rem; color: #fff;
        }
        .profile-name {
            font-size: 0.85rem; font-weight: 600; color: var(--text-main);
            max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .profile-trigger .chevron {
            font-size: 0.55rem; color: var(--text-muted); transition: transform 0.2s ease;
        }
        .nav-dropdown.open .profile-trigger .chevron {
            transform: rotate(180deg);
        }
        .profile-dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            right: 0;
            min-width: 220px;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-dim);
            border-radius: var(--radius-md);
            padding: 0.5rem;
            z-index: 2000;
            box-shadow: 0 12px 40px -8px rgba(0,0,0,0.6);
            animation: dropdownFade 0.15s ease-out;
        }
        .nav-dropdown.open .profile-dropdown-menu {
            display: block;
        }
        .profile-header {
            padding: 0.75rem 0.85rem;
            display: flex; align-items: center; gap: 0.75rem;
        }
        .profile-header-info .name { font-weight: 600; font-size: 0.9rem; color: var(--text-main); }
        .profile-header-info .email { font-size: 0.78rem; color: var(--text-muted); margin-top: 1px; }
        .user-badge {
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.2);
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #818cf8;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .btn-logout {
            display: flex; align-items: center; gap: 0.5rem;
            width: 100%;
            background: transparent;
            border: none;
            color: #f87171;
            padding: 0.6rem 0.85rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.88rem;
            transition: var(--transition-smooth);
            font-family: 'Inter', sans-serif;
        }
        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.1);
        }

        /* Navbar separator */
        .nav-sep {
            width: 1px; height: 22px;
            background: var(--border-dim);
            margin: 0 0.5rem;
            flex-shrink: 0;
        }

        /* Container Main */
        .container {
            max-width: 1200px;
            width: 100%;
            margin: 2rem auto;
            padding: 0 1.5rem;
            flex: 1;
        }

        /* Premium Cards */
        .card {
            background: var(--bg-card);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-dim);
            border-radius: var(--radius-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: transparent;
            transition: var(--transition-smooth);
        }

        .card:hover {
            transform: translateY(-4px);
            border-color: var(--border-glow);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
            background: var(--bg-card-hover);
        }

        .card:hover::before {
            background: var(--primary-gradient);
        }

        /* Typography */
        h1, h2, h3, h4 {
            color: var(--text-main);
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        p {
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* Status Badge */
        .badge {
            padding: 0.35rem 0.75rem;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.1);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.1);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .badge-info {
            background: rgba(14, 165, 233, 0.1);
            color: #38bdf8;
            border: 1px solid rgba(14, 165, 233, 0.2);
        }

        /* Alerts */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            font-weight: 500;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideDown 0.3s ease-out;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
        }

        /* Interactive Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.65rem 1.25rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: var(--transition-smooth);
            border: none;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: #fff;
            box-shadow: 0 4px 14px var(--primary-glow);
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: scale(1.02);
            box-shadow: 0 6px 20px var(--primary-glow);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-main);
            border: 1px solid var(--border-dim);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
            color: #fff;
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            opacity: 0.9;
            transform: scale(1.02);
        }

        /* Skeleton Loading */
        .skeleton {
            background: linear-gradient(90deg,
                rgba(255,255,255,0.04) 25%,
                rgba(255,255,255,0.08) 50%,
                rgba(255,255,255,0.04) 75%
            );
            background-size: 200% 100%;
            animation: shimmer 1.5s ease-in-out infinite;
            border-radius: var(--radius-md);
        }
        .skeleton-text { height: 14px; width: 60%; margin-bottom: 8px; }
        .skeleton-title { height: 20px; width: 40%; margin-bottom: 12px; }
        .skeleton-card { height: 120px; width: 100%; }
        .skeleton-circle { border-radius: 50%; }
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Table Design */
        .table-responsive {
            overflow-x: auto;
            width: 100%;
            margin-top: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: rgba(255, 255, 255, 0.02);
            border-bottom: 1px solid var(--border-dim);
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem 1.25rem;
        }

        td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-dim);
            font-size: 0.95rem;
            color: var(--text-main);
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.01);
        }

        footer {
            text-align: center;
            padding: 2rem;
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-top: auto;
            border-top: 1px solid var(--border-dim);
            background: rgba(9, 13, 22, 0.5);
        }
        /* Toast Notification System */
        .toast-container {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            max-width: 380px;
            width: 100%;
        }
        .toast {
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-dim);
            border-radius: var(--radius-md);
            padding: 1rem 1.25rem;
            box-shadow: 0 10px 30px -5px rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: toastSlideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            position: relative;
            overflow: hidden;
            transition: var(--transition-smooth);
        }
        .toast.hide {
            animation: toastSlideOut 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        .toast::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 4px;
        }
        .toast-success { border-color: rgba(16, 185, 129, 0.25); }
        .toast-success::before { background: #10b981; }
        .toast-success i { color: #10b981; }
        .toast-error { border-color: rgba(239, 68, 68, 0.25); }
        .toast-error::before { background: #ef4444; }
        .toast-error i { color: #ef4444; }
        
        @keyframes toastSlideIn {
            from { transform: translateX(120%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes toastSlideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(120%); opacity: 0; }
        }

        /* Hamburger Button (mobile only) */
        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            color: var(--text-main);
            font-size: 1.4rem;
            cursor: pointer;
            padding: 0.4rem;
            transition: var(--transition-smooth);
        }
        .hamburger-btn:hover { color: var(--primary); }

        /* Responsive Navbar */
        @media (max-width: 900px) {
            .navbar {
                flex-wrap: wrap;
                padding: 1rem 1.25rem;
            }
            .hamburger-btn {
                display: block;
            }
            .nav-links {
                display: none;
                flex-direction: column;
                width: 100%;
                gap: 0.25rem;
                padding-top: 0.75rem;
                border-top: 1px solid var(--border-dim);
                margin-top: 0.75rem;
                align-items: stretch;
            }
            .nav-links.open {
                display: flex;
            }
            .nav-link, .nav-dropdown-trigger {
                width: 100%;
                padding: 0.75rem 1rem;
                font-size: 0.95rem;
            }
            .nav-dropdown-menu, .profile-dropdown-menu {
                position: static;
                box-shadow: none;
                border: none;
                background: rgba(255,255,255,0.02);
                border-radius: 8px;
                margin-top: 0.25rem;
                min-width: 100%;
            }
            .nav-sep { display: none; }
            .profile-trigger {
                width: 100%;
                justify-content: flex-start;
                padding: 0.75rem 1rem;
            }
            .profile-name { max-width: none; }
            .showcase-grid {
                grid-template-columns: 1fr !important;
            }
        }

        /* Custom Confirm Modal */
        .confirm-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.6); z-index: 99999;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }
        .confirm-overlay.open { opacity: 1; visibility: visible; }
        .confirm-box {
            background: var(--bg-card); backdrop-filter: blur(20px);
            border: 1px solid var(--border-dim); border-radius: var(--radius-lg);
            padding: 2rem; width: 90%; max-width: 400px;
            transform: scale(0.9) translateY(20px);
            transition: transform 0.25s ease;
        }
        .confirm-overlay.open .confirm-box { transform: scale(1) translateY(0); }
        .confirm-icon {
            width: 48px; height: 48px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; margin: 0 auto 1rem;
        }
        .confirm-icon.warning { background: rgba(245,158,11,0.15); color: #fbbf24; }
        .confirm-icon.danger { background: rgba(239,68,68,0.15); color: #f87171; }
        .confirm-icon.success { background: rgba(16,185,129,0.15); color: #34d399; }
        .confirm-title { font-size: 1.1rem; font-weight: 700; text-align: center; margin-bottom: 0.5rem; }
        .confirm-msg { font-size: 0.88rem; color: var(--text-muted); text-align: center; margin-bottom: 1.5rem; line-height: 1.5; }
        .confirm-actions { display: flex; gap: 0.75rem; justify-content: center; }
        .confirm-actions .btn { min-width: 100px; justify-content: center; }
    </style>
    @yield('styles')
</head>
<body>

    {{-- Scroll Progress Bar --}}
    <div class="scroll-progress" id="scrollProgress"></div>

    @auth
    <nav class="navbar">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <a href="/dashboard" class="navbar-brand">
                <i class="fa-solid fa-shield-halved"></i> Vanguard<span>Asset</span>
            </a>
            <button class="hamburger-btn" onclick="document.querySelector('.nav-links').classList.toggle('open')">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
        <div class="nav-links">
            {{-- Dashboard --}}
            <a href="/dashboard" class="nav-link"><i class="fa-solid fa-chart-line"></i> Dashboard</a>

            {{-- Aset Dropdown --}}
            <div class="nav-dropdown" data-dropdown>
                <button class="nav-dropdown-trigger">
                    <i class="fa-solid fa-boxes-stacked"></i> Aset
                    <i class="fa-solid fa-chevron-down chevron"></i>
                </button>
                <div class="nav-dropdown-menu">
                    <a href="{{ route('assets.index') }}" class="nav-dropdown-item">
                        <i class="fa-solid fa-list"></i> Katalog Aset
                    </a>
                    @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                    @php $maintDue = \App\Services\MaintenanceSchedulerService::getMaintenanceDue()->count(); @endphp
                    <a href="{{ route('assets.maintenance') }}" class="nav-dropdown-item">
                        <i class="fa-solid fa-screwdriver-wrench"></i> Maintenance
                        @if($maintDue > 0)
                            <span class="nav-badge warn" style="margin-left:auto;">{{ $maintDue }}</span>
                        @endif
                    </a>
                    @endif
                    @if(Auth::user()->isAdmin())
                    <div class="nav-dropdown-divider"></div>
                    <a href="{{ route('assets.create') }}" class="nav-dropdown-item">
                        <i class="fa-solid fa-plus-circle"></i> Tambah Aset Baru
                    </a>
                    @endif
                </div>
            </div>

            {{-- Peminjaman --}}
            <a href="{{ route('approvals.index') }}" class="nav-link">
                <i class="fa-solid fa-ticket"></i> Peminjaman
                @php
                    $navPendingCount = \App\Models\ApprovalRequest::where('status', 'Pending')->count();
                    $navOverdueCount = \App\Models\ApprovalRequest::overdue()->count();
                    $navTotalNotif = $navPendingCount + $navOverdueCount;
                @endphp
                @if($navTotalNotif > 0)
                    <span class="nav-badge" style="{{ $navOverdueCount > 0 ? 'background:#ef4444;' : '' }}">
                        {{ $navTotalNotif }}
                    </span>
                @endif
            </a>

            {{-- OOP Showcase --}}
            <a href="{{ route('oop.showcase') }}" class="nav-link" style="color: #c084fc;">
                <i class="fa-solid fa-graduation-cap"></i> OOP Showcase
            </a>

            {{-- Admin Tools Dropdown --}}
            @if(Auth::user()->isAdmin())
            <div class="nav-dropdown" data-dropdown>
                <button class="nav-dropdown-trigger">
                    <i class="fa-solid fa-gear"></i> Admin
                    <i class="fa-solid fa-chevron-down chevron"></i>
                </button>
                <div class="nav-dropdown-menu">
                    <a href="{{ route('security.dashboard') }}" class="nav-dropdown-item">
                        <i class="fa-solid fa-shield-halved"></i> Keamanan
                    </a>
                    <a href="{{ route('users.index') }}" class="nav-dropdown-item">
                        <i class="fa-solid fa-users-gear"></i> User Management
                    </a>
                </div>
            </div>
            @endif

            {{-- Separator --}}
            <div class="nav-sep"></div>

            {{-- Profile Dropdown --}}
            <div class="nav-dropdown" data-dropdown>
                <button class="profile-trigger">
                    <div class="profile-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    <span class="profile-name">{{ Auth::user()->name }}</span>
                    <i class="fa-solid fa-chevron-down chevron"></i>
                </button>
                <div class="profile-dropdown-menu">
                    <div class="profile-header">
                        <div class="profile-avatar" style="width:38px;height:38px;font-size:0.9rem;">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        <div class="profile-header-info">
                            <div class="name">{{ Auth::user()->name }}</div>
                            <div class="email">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    <div class="nav-dropdown-divider"></div>
                    <div style="padding: 0.5rem 0.85rem;">
                        <span class="user-badge">
                            @if(Auth::user()->role === 'admin') IT Admin
                            @elseif(Auth::user()->role === 'manager') Manajer
                            @else Staf
                            @endif
                        </span>
                    </div>
                    <div class="nav-dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn-logout"><i class="fa-solid fa-sign-out-alt"></i> Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <div class="container">
    <div class="toast-container" id="toast-container">
        @if(session('success'))
            <div class="toast toast-success" role="alert">
                <i class="fa-solid fa-circle-check"></i>
                <div style="flex: 1; font-size: 0.9rem; font-weight: 500;">{{ session('success') }}</div>
                <button onclick="this.parentElement.classList.add('hide'); setTimeout(() => this.parentElement.remove(), 300)" style="background: none; border: none; color: var(--text-muted); cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif
        @if(session('error'))
            <div class="toast toast-error" role="alert">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div style="flex: 1; font-size: 0.9rem; font-weight: 500;">{{ session('error') }}</div>
                <button onclick="this.parentElement.classList.add('hide'); setTimeout(() => this.parentElement.remove(), 300)" style="background: none; border: none; color: var(--text-muted); cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif
    </div>

        @yield('content')
    </div>

    <footer>
        <p>&copy; 2026 VanguardAsset. Dikembangkan dengan Laravel 11 & Python FastAPI (Strict OOP Logic Engine). Hak Cipta Dilindungi.</p>
    </footer>

    @yield('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toast auto-dismiss
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.classList.add('hide');
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            });

            // Dropdown toggle
            document.querySelectorAll('[data-dropdown]').forEach(dd => {
                const trigger = dd.querySelector('.nav-dropdown-trigger, .profile-trigger');
                if (!trigger) return;
                trigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    document.querySelectorAll('[data-dropdown].open').forEach(other => {
                        if (other !== dd) other.classList.remove('open');
                    });
                    dd.classList.toggle('open');
                });
            });

            // Close dropdowns on outside click
            document.addEventListener('click', function(e) {
                if (!e.target.closest('[data-dropdown]')) {
                    document.querySelectorAll('[data-dropdown].open').forEach(dd => dd.classList.remove('open'));
                }
            });

            // Close mobile menu on nav click
            document.querySelectorAll('.nav-link, .nav-dropdown-item').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 900) {
                        document.querySelector('.nav-links').classList.remove('open');
                    }
                });
            });

            // Scroll Progress Bar
            const progressBar = document.getElementById('scrollProgress');
            if (progressBar) {
                window.addEventListener('scroll', function() {
                    const scrollTop = window.scrollY;
                    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                    const scrollPercent = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
                    progressBar.style.width = scrollPercent + '%';
                }, { passive: true });
            }

            // Count-up Animation untuk angka statistik
            function formatCompact(num) {
                if (num >= 1e9) return (num / 1e9).toFixed(2).replace('.', ',') + ' M';
                if (num >= 1e6) return (num / 1e6).toFixed(1).replace('.', ',') + ' Jt';
                if (num >= 1e3) return (num / 1e3).toFixed(1).replace('.', ',') + ' Rb';
                return Math.round(num).toLocaleString('id-ID');
            }

            document.querySelectorAll('[data-count]').forEach(el => {
                const target = parseFloat(el.getAttribute('data-count'));
                const duration = 1200;
                const startTime = performance.now();
                const isCurrency = el.classList.contains('count-currency') || el.classList.contains('currency-num');
                const decimals = el.classList.contains('count-decimal') ? 2 : 0;

                function animateCount(now) {
                    const elapsed = now - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    // easeOutExpo
                    const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
                    const current = target * eased;

                    if (isCurrency) {
                        el.textContent = formatCompact(current);
                    } else {
                        el.textContent = decimals > 0 ? current.toFixed(decimals) : Math.round(current).toLocaleString('id-ID');
                    }

                    if (progress < 1) {
                        requestAnimationFrame(animateCount);
                    }
                }
                requestAnimationFrame(animateCount);
            });

            // 3D Card Tilt Effect
            document.querySelectorAll('[data-tilt]').forEach(card => {
                card.addEventListener('mousemove', function(e) {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    const rotateX = ((y - centerY) / centerY) * -6;
                    const rotateY = ((x - centerX) / centerX) * 6;
                    card.style.transform = 'perspective(800px) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg) scale3d(1.02,1.02,1.02)';
                });
                card.addEventListener('mouseleave', function() {
                    card.style.transform = 'perspective(800px) rotateX(0) rotateY(0) scale3d(1,1,1)';
                    card.style.transition = 'transform 0.4s ease-out';
                });
                card.addEventListener('mouseenter', function() {
                    card.style.transition = 'transform 0.1s ease-out';
                });
            });
        });

        // ─── Custom Confirm System (no native popups) ───
        let pendingConfirmForm = null;

        function showConfirm(title, msg, type, okText, okClass, form) {
            document.getElementById('confirmTitle').textContent = title;
            document.getElementById('confirmMsg').textContent = msg;
            const icon = document.getElementById('confirmIcon');
            icon.className = 'confirm-icon ' + type;
            const icons = { warning: 'fa-triangle-exclamation', danger: 'fa-trash', success: 'fa-check' };
            icon.innerHTML = '<i class="fa-solid ' + (icons[type] || 'fa-question') + '"></i>';
            const okBtn = document.getElementById('confirmOkBtn');
            okBtn.textContent = okText || 'Ya, Lanjutkan';
            okBtn.className = 'btn ' + (okClass || 'btn-primary');
            pendingConfirmForm = form;
            document.getElementById('confirmOverlay').classList.add('open');
        }

        function closeConfirm() {
            document.getElementById('confirmOverlay').classList.remove('open');
            pendingConfirmForm = null;
        }

        function executeConfirm() {
            if (pendingConfirmForm) pendingConfirmForm.submit();
            closeConfirm();
        }

        // Intercept forms with data-confirm attribute
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.dataset.confirm && !form.dataset.confirmed) {
                e.preventDefault();
                const msg = form.dataset.confirm;
                const type = form.dataset.confirmType || 'warning';
                const title = form.dataset.confirmTitle || 'Konfirmasi';
                const okText = form.dataset.confirmOk || 'Ya, Lanjutkan';
                const okClass = form.dataset.confirmClass || 'btn-primary';
                showConfirm(title, msg, type, okText, okClass, form);
            }
        });

        // Close modal on backdrop click
        document.getElementById('confirmOverlay')?.addEventListener('click', function(e) {
            if (e.target === this) closeConfirm();
        });
    </script>

    {{-- Global Confirm Modal --}}
    <div class="confirm-overlay" id="confirmOverlay">
        <div class="confirm-box">
            <div class="confirm-icon warning" id="confirmIcon"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div class="confirm-title" id="confirmTitle">Konfirmasi</div>
            <div class="confirm-msg" id="confirmMsg">Apakah Anda yakin?</div>
            <div class="confirm-actions">
                <button class="btn btn-secondary" onclick="closeConfirm()">Batal</button>
                <button class="btn btn-primary" id="confirmOkBtn" onclick="executeConfirm()">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>
</body>
</html>
