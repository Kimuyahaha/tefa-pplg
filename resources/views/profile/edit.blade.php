<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Profil Saya
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto space-y-6">

            <div class="bg-white p-6 rounded-xl shadow-sm">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm">
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</x-app-layout>