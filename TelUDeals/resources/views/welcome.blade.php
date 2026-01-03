<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>

    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen flex items-center justify-center bg-[#7b0f2b]">

    <div class="bg-white rounded-xl shadow p-8 w-full max-w-sm text-center">
        <h1 class="text-2xl font-bold mb-6">Tel-U Deals</h1>

        @auth
            <a href="{{ url('/dashboard') }}"
               class="block w-full bg-red-600 text-white py-2 rounded mb-3">
                Dashboard
            </a>
        @else
            <a href="{{ route('login') }}"
               class="block w-full bg-red-600 text-white py-2 rounded mb-3">
                Log in
            </a>

            <a href="{{ route('register') }}"
               class="block w-full border border-red-600 text-red-600 py-2 rounded">
                Register
            </a>
        @endauth
    </div>

</body>
</html>
