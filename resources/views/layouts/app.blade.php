<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            :root {
                color-scheme: light;
            }
            
            /* Dark Mode Styling */
            body.dark-mode {
                background-color: #111827 !important;
                color: #f3f4f6 !important;
            }
            body.dark-mode * {
                color: #f3f4f6 !important;
            }
            body.dark-mode .bg-gray-100 { background-color: #1f2937 !important; }
            body.dark-mode .bg-white { background-color: #1f2937 !important; }
            body.dark-mode .bg-gray-50 { background-color: #111827 !important; }
            body.dark-mode .text-gray-900 { color: #f3f4f6 !important; }
            body.dark-mode .text-gray-700 { color: #d1d5db !important; }
            body.dark-mode .text-gray-600 { color: #9ca3af !important; }
            body.dark-mode .text-gray-500 { color: #6b7280 !important; }
            body.dark-mode .text-gray-800 { color: #e5e7eb !important; }
            body.dark-mode .border-gray-300 { border-color: #4b5563 !important; }
            body.dark-mode .border-gray-200 { border-color: #374151 !important; }
            body.dark-mode .shadow { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.5) !important; }
            body.dark-mode .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.3) !important; }
            
            /* Dark Mode - Form Elements */
            body.dark-mode input[type="text"],
            body.dark-mode input[type="email"],
            body.dark-mode input[type="password"],
            body.dark-mode input[type="file"],
            body.dark-mode textarea,
            body.dark-mode select {
                background-color: #374151 !important;
                color: #f3f4f6 !important;
                border-color: #4b5563 !important;
            }
            body.dark-mode input[type="text"]::placeholder,
            body.dark-mode input[type="email"]::placeholder,
            body.dark-mode input[type="password"]::placeholder,
            body.dark-mode textarea::placeholder {
                color: #9ca3af !important;
            }
            
            /* Dark Mode - Button Hover */
            body.dark-mode .hover\:bg-gray-200:hover { background-color: #374151 !important; }
            body.dark-mode .hover\:shadow:hover { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.5) !important; }
            
            /* Dark Mode - Table */
            body.dark-mode table { background-color: #1f2937 !important; }
            body.dark-mode thead { background-color: #111827 !important; }
            body.dark-mode tbody tr:hover { background-color: #374151 !important; }
            body.dark-mode tbody td { border-color: #374151 !important; }
            
            /* Dark Mode - Navigation */
            body.dark-mode header { background-color: #1f2937 !important; border-color: #374151 !important; }
            body.dark-mode nav { background-color: #1f2937 !important; border-color: #374151 !important; }
            body.dark-mode .border-b { border-color: #374151 !important; }
            
            /* Dark Mode - Cards */
            body.dark-mode .overflow-hidden { background-color: #1f2937 !important; }
            body.dark-mode .rounded-lg { background-color: #1f2937 !important; }
            
            /* Font Sizing */
            body {
                font-size: 16px;
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
            label {
                font-size: 16px;
                font-weight: 500;
            }
            p {
                font-size: 16px;
            }
        </style>
    </head>
    <body class="font-sans antialiased" id="userBody">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                        <div>
                            {{ $header }}
                        </div>
                        <button id="userThemeToggle" class="px-4 py-2 bg-gray-800 text-white rounded-lg font-semibold hover:bg-gray-700 transition" title="Toggle Dark/Light Mode">
                            Dark Mode
                        </button>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        
        <script>
            // User Dashboard Dark Mode Toggle
            const userThemeToggle = document.getElementById('userThemeToggle');
            const userBody = document.getElementById('userBody');
            
            // Load saved theme
            const savedUserTheme = localStorage.getItem('userDashboardTheme') || 'light';
            if (savedUserTheme === 'dark') {
                userBody.classList.add('dark-mode');
                userThemeToggle.textContent = 'Light Mode';
            }
            
            // Toggle theme
            userThemeToggle.addEventListener('click', function() {
                const isDarkMode = userBody.classList.contains('dark-mode');
                
                if (isDarkMode) {
                    userBody.classList.remove('dark-mode');
                    localStorage.setItem('userDashboardTheme', 'light');
                    this.textContent = 'Dark Mode';
                } else {
                    userBody.classList.add('dark-mode');
                    localStorage.setItem('userDashboardTheme', 'dark');
                    this.textContent = 'Light Mode';
                }
            });
        </script>
    </body>
</html>
