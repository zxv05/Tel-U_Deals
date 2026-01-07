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

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-[#7b0f2b] text-gray-900">
    <div class="min-h-screen bg-[#7b0f2b]">

        @include('layouts.navigation')

        @isset($header)
            <header class="bg-[#8B1538] shadow">
                <div class="max-w-7xl mx-auto py-6 px-6 text-white">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main >
            {{ $slot }}
        </main>
    </div>

       <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- GLOBAL TOAST FUNCTION -->
    <script>
        function showToast(type, message) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type, // success | error | warning | info
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        }
    </script>

    <!-- TOAST FROM BACKEND (SESSION) -->
    @if(session('success'))
        <script>
            showToast('success', "{{ session('success') }}");
        </script>
    @endif

    @if(session('error'))
        <script>
            showToast('error', "{{ session('error') }}");
        </script>
    @endif

    <!-- PAGE SCRIPT -->
    @stack('scripts')
</body>
</html>

