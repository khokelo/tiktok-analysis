<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard' }} - TikTok Analysis</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <style>
        :root {
            color-scheme: dark;
        }
        
        /* Light Mode - All text dark, light backgrounds */
        body.light-mode {
            background-color: #f8f9fa !important;
            color: #1a1a1a !important;
        }
        body.light-mode * {
            color: #1a1a1a !important;
        }
        body.light-mode .bg-gray-900 { background-color: #f8f9fa !important; }
        body.light-mode .bg-gray-800 { background-color: #e9ecef !important; }
        body.light-mode .bg-gray-700 { background-color: #dee2e6 !important; }
        body.light-mode .bg-gray-600 { background-color: #adb5bd !important; }
        body.light-mode .text-white { color: #1a1a1a !important; }
        body.light-mode .text-gray-100 { color: #1a1a1a !important; }
        body.light-mode .text-gray-200 { color: #2d2d2d !important; }
        body.light-mode .text-gray-300 { color: #404040 !important; }
        body.light-mode .text-gray-400 { color: #555 !important; }
        body.light-mode .text-gray-500 { color: #666 !important; }
        body.light-mode .border-gray-700 { border-color: #ccc !important; }
        body.light-mode .border-gray-800 { border-color: #bbb !important; }
        body.light-mode .border-gray-900 { border-color: #aaa !important; }
        
        /* Light Mode - Form Elements */
        body.light-mode input,
        body.light-mode textarea,
        body.light-mode select,
        body.light-mode .card,
        body.light-mode .table {
            background-color: white !important;
            color: #1a1a1a !important;
            border-color: #ddd !important;
        }
        body.light-mode input::placeholder {
            color: #999 !important;
        }
        
        /* Light Mode - Hover Effects */
        body.light-mode .hover\:bg-gray-700:hover { background-color: #dee2e6 !important; }
        body.light-mode .hover\:bg-gray-600:hover { background-color: #adb5bd !important; }
        body.light-mode .hover\:text-white:hover { color: #1a1a1a !important; }
        
        /* Light Mode - Gradients */
        body.light-mode .bg-gradient-to-br {
            filter: brightness(0.85) !important;
        }
        body.light-mode .bg-blue-900 { background-color: #cfe2ff !important; }
        body.light-mode .text-blue-200 { color: #084298 !important; }
        body.light-mode .text-blue-300 { color: #0c63e4 !important; }
        
        body.light-mode .bg-purple-900 { background-color: #e2d5ff !important; }
        body.light-mode .text-purple-200 { color: #5e378c !important; }
        body.light-mode .text-purple-300 { color: #7952b3 !important; }
        
        body.light-mode .bg-green-900 { background-color: #d1e7dd !important; }
        body.light-mode .text-green-200 { color: #0f5132 !important; }
        body.light-mode .text-green-300 { color: #198754 !important; }
        
        body.light-mode .bg-orange-900 { background-color: #ffe5cc !important; }
        body.light-mode .text-orange-200 { color: #7d4e24 !important; }
        body.light-mode .text-orange-300 { color: #fd7e14 !important; }
        
        /* Light Mode - Special Colors */
        body.light-mode .text-indigo-400 { color: #3f51b5 !important; }
        body.light-mode .text-indigo-500 { color: #3f51b5 !important; }
        
        /* Navigation */
        .nav-link.active {
            background-color: rgba(99, 102, 241, 0.2);
            border-left: 3px solid rgb(99, 102, 241);
            color: rgb(129, 140, 248);
        }
        body.light-mode .nav-link.active {
            background-color: rgba(99, 102, 241, 0.1);
            color: rgb(99, 102, 241);
        }
        
        .top-nav-link.active {
            border-bottom: 3px solid rgb(99, 102, 241);
            color: rgb(129, 140, 248);
        }
        body.light-mode .top-nav-link.active {
            color: rgb(99, 102, 241);
        }
        
        /* Font Sizing */
        body {
            font-size: 18px;
            line-height: 1.6;
        }
        h1 {
            font-size: 2.2em;
            font-weight: 700;
            margin-bottom: 0.5em;
        }
        h2 {
            font-size: 1.8em;
            font-weight: 700;
            margin-bottom: 0.5em;
        }
        h3 {
            font-size: 1.4em;
            font-weight: 600;
            margin-bottom: 0.3em;
        }
        h4, h5, h6 {
            font-size: 1.2em;
            font-weight: 600;
            margin-bottom: 0.3em;
        }
        .table {
            font-size: 17px;
            line-height: 1.8;
        }
        .btn {
            font-size: 16px;
            padding: 10px 18px;
            font-weight: 500;
        }
        p {
            font-size: 17px;
        }
        label {
            font-size: 17px;
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100" id="themeBody">
    <!-- Top Navigation Bar -->
    <nav class="bg-gray-800 border-b border-gray-700 sticky top-0 z-50">
        <div class="max-w-full px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <div class="text-2xl font-bold text-indigo-500">TikTok</div>
                    <div>
                        <h1 class="text-lg font-bold text-indigo-500">Analytics</h1>
                        <p class="text-xs text-gray-400">Admin Dashboard</p>
                    </div>
                </div>

                <!-- Top Navigation Links -->
                <div class="flex items-center space-x-1">
                    <a href="{{ route('admin.dashboard') }}" class="top-nav-link px-4 py-2 rounded-t-lg transition text-gray-300 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.sales.index') }}" class="top-nav-link px-4 py-2 rounded-t-lg transition text-gray-300 hover:text-white {{ request()->routeIs('admin.sales.*') ? 'active' : '' }}">
                        Sales
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="top-nav-link px-4 py-2 rounded-t-lg transition text-gray-300 hover:text-white {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        Users
                    </a>
                    <a href="{{ route('admin.files.index') }}" class="top-nav-link px-4 py-2 rounded-t-lg transition text-gray-300 hover:text-white {{ request()->routeIs('admin.files.*') ? 'active' : '' }}">
                        Files
                    </a>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-3 border-l border-gray-700 pl-4">
                    <div class="text-right text-sm">
                        <p class="font-semibold text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-indigo-400">Admin</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button id="themeToggle" class="px-3 py-1 text-sm bg-gray-700 hover:bg-gray-600 text-gray-300 rounded transition font-semibold" title="Toggle Dark/Light Mode">
                            Light
                        </button>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1 text-sm bg-red-600 hover:bg-red-700 text-white rounded transition font-semibold">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex min-h-screen pt-0">
        <!-- Optional Sidebar (smaller version) -->
        <aside class="w-64 bg-gray-800 border-r border-gray-700 hidden lg:flex flex-col">
            <nav class="p-4 space-y-1 flex-1">
                <div class="text-xs font-semibold text-gray-500 px-4 py-2 uppercase">MENU</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link block px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-gray-400 hover:bg-gray-700' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.sales.index') }}" class="nav-link block px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.sales.*') ? 'active' : 'text-gray-400 hover:bg-gray-700' }}">
                    Sales Management
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-link block px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.users.*') ? 'active' : 'text-gray-400 hover:bg-gray-700' }}">
                    User Management
                </a>
                <a href="{{ route('admin.files.index') }}" class="nav-link block px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.files.*') ? 'active' : 'text-gray-400 hover:bg-gray-700' }}">
                    File Management
                </a>
                <hr class="my-3 border-gray-700">
                <div class="text-xs font-semibold text-gray-500 px-4 py-2 uppercase">LAINNYA</div>
                <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-700 transition">
                    User Dashboard
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col">
            <!-- Page Header -->
            <header class="bg-gray-800 border-b border-gray-700 px-6 py-4">
                <h2 class="text-3xl font-bold text-white">{{ $title ?? 'Dashboard' }}</h2>
                @if(isset($subtitle))
                    <p class="text-gray-400 text-sm mt-1">{{ $subtitle }}</p>
                @endif
            </header>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-6">
                <!-- Messages -->
                @if ($message = session('success'))
                    <div class="mb-4 p-4 bg-green-900 border border-green-700 text-green-200 rounded-lg flex items-start">
                        <span class="mr-3 text-lg">✅</span>
                        <div>
                            <h3 class="font-semibold">Success</h3>
                            <p class="text-sm">{{ $message }}</p>
                        </div>
                    </div>
                @endif

                @if ($message = session('error'))
                    <div class="mb-4 p-4 bg-red-900 border border-red-700 text-red-200 rounded-lg flex items-start">
                        <span class="mr-3 text-lg">❌</span>
                        <div>
                            <h3 class="font-semibold">Error</h3>
                            <p class="text-sm">{{ $message }}</p>
                        </div>
                    </div>
                @endif

                @if ($message = session('warning'))
                    <div class="mb-4 p-4 bg-yellow-900 border border-yellow-700 text-yellow-200 rounded-lg flex items-start">
                        <span class="mr-3 text-lg">⚠️</span>
                        <div>
                            <h3 class="font-semibold">Warning</h3>
                            <p class="text-sm">{{ $message }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="bg-gray-800 border-t border-gray-700 px-6 py-4 text-center text-gray-400 text-sm">
                <p>&copy; 2026 TikTok Sales Analysis Dashboard. All rights reserved.</p>
            </footer>
        </main>
    </div>
    <script>
        // Dark/Light Mode Toggle
        document.getElementById('themeToggle').addEventListener('click', function() {
            const body = document.getElementById('themeBody');
            const isDarkMode = body.classList.contains('bg-gray-900');
            
            if (isDarkMode) {
                // Switch to light mode
                body.classList.remove('bg-gray-900', 'text-gray-100');
                body.classList.add('light-mode');
                localStorage.setItem('theme', 'light');
                document.getElementById('themeToggle').textContent = 'Dark';
            } else {
                // Switch to dark mode
                body.classList.add('bg-gray-900', 'text-gray-100');
                body.classList.remove('light-mode');
                localStorage.setItem('theme', 'dark');
                document.getElementById('themeToggle').textContent = 'Light';
            }
        });
        
        // Load saved theme on page load
        window.addEventListener('DOMContentLoaded', function() {
            const theme = localStorage.getItem('theme') || 'dark';
            const body = document.getElementById('themeBody');
            const themeToggle = document.getElementById('themeToggle');
            
            if (theme === 'light') {
                body.classList.remove('bg-gray-900', 'text-gray-100');
                body.classList.add('light-mode');
                themeToggle.textContent = 'Dark';
            }
        });
    </script>
</body>
</html>
