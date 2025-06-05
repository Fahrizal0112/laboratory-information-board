<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laboratory Information Board') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Custom scrollbar for webkit browsers */
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }
            
            ::-webkit-scrollbar-track {
                background: #f7f7f7;
            }
            
            ::-webkit-scrollbar-thumb {
                background: #dc2626;
                border-radius: 4px;
            }
            
            ::-webkit-scrollbar-thumb:hover {
                background: #b91c1c;
            }
            
            /* Sticky footer styles */
            body {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }
            
            main {
                flex: 1;
            }
            
            footer {
                position: sticky;
                top: 100%;
            }
        </style>
    </head>
    <body class="font-sans antialiased flex flex-col min-h-screen bg-red-50">
        <div class="flex flex-col flex-grow">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow border-b border-red-100">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-grow">
                {{ $slot }}
            </main>
        </div>
        
        <!-- Footer -->
        <footer class="bg-red-800 text-white py-4 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm">&copy; {{ date('Y') }} Laboratory Information Board. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
