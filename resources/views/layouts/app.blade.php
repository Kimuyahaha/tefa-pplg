<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Lab TEFA PPLG') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            {{ $slot }}
        </main>
    </div>

    @if(session('success'))
        <div id="toastSuccess"
            class="fixed top-6 left-1/2 -translate-x-1/2 z-50 bg-white border border-green-200 shadow-xl rounded-xl px-6 py-4">
            <p class="text-sm font-semibold text-green-700">
                {{ session('success') }}
            </p>
        </div>
    @endif

    @if(session('error'))
        <div id="toastError"
            class="fixed top-6 left-1/2 -translate-x-1/2 z-50 bg-white border border-red-200 shadow-xl rounded-xl px-6 py-4">
            <p class="text-sm font-semibold text-red-700">
                {{ session('error') }}
            </p>
        </div>
    @endif

    <div id="deleteModal" class="hidden fixed inset-0 z-50 bg-black/60 items-center justify-center px-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-7 text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                Yakin ingin hapus?
            </h2>

            <div class="flex justify-center gap-3">
                <button
                    type="button"
                    id="cancelDelete"
                    class="px-6 py-2 rounded-full border border-red-800 text-red-800 font-semibold hover:bg-red-50">
                    Batal
                </button>

                <button
                    type="button"
                    id="confirmDelete"
                    class="px-6 py-2 rounded-full bg-red-800 text-white font-semibold hover:bg-red-900">
                    Ya, hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        let selectedDeleteForm = null;

        document.querySelectorAll('form').forEach(function (form) {
            const methodInput = form.querySelector('input[name="_method"]');

            if (methodInput && methodInput.value.toUpperCase() === 'DELETE') {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    selectedDeleteForm = form;

                    const modal = document.getElementById('deleteModal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            }
        });

        document.getElementById('cancelDelete')?.addEventListener('click', function () {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            selectedDeleteForm = null;
        });

        document.getElementById('confirmDelete')?.addEventListener('click', function () {
            if (selectedDeleteForm) {
                selectedDeleteForm.submit();
            }
        });

        setTimeout(function () {
            document.getElementById('toastSuccess')?.remove();
            document.getElementById('toastError')?.remove();
        }, 2500);
    </script>
</body>
</html>