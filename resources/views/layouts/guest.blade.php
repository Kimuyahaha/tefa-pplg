<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Lab TEFA PPLG') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-[#f5f5f5]">
    <div class="min-h-screen flex items-center justify-center px-4 relative overflow-hidden">

        <div class="absolute -left-24 top-0 w-72 h-full bg-white rounded-r-[45%] opacity-70"></div>
        <div class="absolute -right-24 top-0 w-72 h-full bg-white rounded-l-[45%] opacity-70"></div>

        <div class="relative w-full max-w-md bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="h-1.5 bg-red-800"></div>

            <div class="px-8 py-9">
                <div class="text-center mb-7">
                    <img
                        src="{{ asset('images/smktelkombjb.png') }}"
                        alt="Logo SMK Telkom Banjarbaru"
                        class="h-16 mx-auto object-contain mb-4"
                    >

                    <h1 class="text-lg font-bold text-gray-900">
                        Login Lab TEFA PPLG
                    </h1>

                    <p class="text-sm text-gray-500 mt-2">
                        Masuk untuk mengelola peminjaman peralatan
                    </p>
                </div>

                {{ $slot }}

                <p class="text-center text-xs text-gray-400 mt-8">
                    © {{ date('Y') }} SMK Telkom Banjarbaru
                </p>
            </div>
        </div>
    </div>
</body>
</html>