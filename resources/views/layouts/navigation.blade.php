<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">

            <div class="flex items-center gap-10">
                <a href="{{ route('dashboard') }}" class="leading-tight">
                    <p class="text-red-800 font-bold text-xl">
                        Lab TEFA PPLG
                    </p>
                    <p class="text-gray-500 text-xs">
                        Sistem Peminjaman Peralatan
                    </p>
                </a>

                <div class="hidden sm:flex items-center gap-2">
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 rounded-lg text-sm font-semibold transition
                        {{ request()->routeIs('dashboard')
                            ? 'bg-red-800 text-white shadow-sm'
                            : 'text-gray-600 hover:bg-red-50 hover:text-red-800' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('peralatans.index') }}"
                        class="px-4 py-2 rounded-lg text-sm font-semibold transition
                        {{ request()->routeIs('peralatans.*')
                            ? 'bg-red-800 text-white shadow-sm'
                            : 'text-gray-600 hover:bg-red-50 hover:text-red-800' }}">
                        Peralatan
                    </a>

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('peminjamans.index') }}"
                            class="px-4 py-2 rounded-lg text-sm font-semibold transition
                            {{ request()->routeIs('peminjamans.index')
                                ? 'bg-red-800 text-white shadow-sm'
                                : 'text-gray-600 hover:bg-red-50 hover:text-red-800' }}">
                            Data Peminjaman
                        </a>

                        <a href="{{ route('peminjamans.riwayat-pengembalian') }}"
                            class="px-4 py-2 rounded-lg text-sm font-semibold transition
                            {{ request()->routeIs('peminjamans.riwayat-pengembalian')
                                ? 'bg-red-800 text-white shadow-sm'
                                : 'text-gray-600 hover:bg-red-50 hover:text-red-800' }}">
                            Riwayat Pengembalian
                        </a>
                    @else
                        <a href="{{ route('peminjamans.saya') }}"
                            class="px-4 py-2 rounded-lg text-sm font-semibold transition
                            {{ request()->routeIs('peminjamans.saya')
                                ? 'bg-red-800 text-white shadow-sm'
                                : 'text-gray-600 hover:bg-red-50 hover:text-red-800' }}">
                            Peminjaman Saya
                        </a>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition">
                            <span>{{ Auth::user()->nama }}</span>

                            <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profil
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Keluar
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-red-800 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />

                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-white border-t border-gray-200">
        <div class="px-4 py-3 space-y-2">
            <a href="{{ route('dashboard') }}"
                class="block px-4 py-2 rounded-lg text-sm font-semibold
                {{ request()->routeIs('dashboard') ? 'bg-red-800 text-white' : 'text-gray-600 hover:bg-red-50 hover:text-red-800' }}">
                Dashboard
            </a>

            <a href="{{ route('peralatans.index') }}"
                class="block px-4 py-2 rounded-lg text-sm font-semibold
                {{ request()->routeIs('peralatans.*') ? 'bg-red-800 text-white' : 'text-gray-600 hover:bg-red-50 hover:text-red-800' }}">
                Peralatan
            </a>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('peminjamans.index') }}"
                    class="block px-4 py-2 rounded-lg text-sm font-semibold
                    {{ request()->routeIs('peminjamans.index') ? 'bg-red-800 text-white' : 'text-gray-600 hover:bg-red-50 hover:text-red-800' }}">
                    Data Peminjaman
                </a>

                <a href="{{ route('peminjamans.riwayat-pengembalian') }}"
                    class="block px-4 py-2 rounded-lg text-sm font-semibold
                    {{ request()->routeIs('peminjamans.riwayat-pengembalian') ? 'bg-red-800 text-white' : 'text-gray-600 hover:bg-red-50 hover:text-red-800' }}">
                    Riwayat Pengembalian
                </a>
            @else
                <a href="{{ route('peminjamans.saya') }}"
                    class="block px-4 py-2 rounded-lg text-sm font-semibold
                    {{ request()->routeIs('peminjamans.saya') ? 'bg-red-800 text-white' : 'text-gray-600 hover:bg-red-50 hover:text-red-800' }}">
                    Peminjaman Saya
                </a>
            @endif
        </div>

        <div class="px-4 py-4 border-t border-gray-200">
            <p class="font-semibold text-gray-900">
                {{ Auth::user()->nama }}
            </p>
            <p class="text-sm text-gray-500">
                {{ Auth::user()->email }}
            </p>

            <div class="mt-3 space-y-2">
                <a href="{{ route('profile.edit') }}"
                    class="block px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-100">
                    Profil
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                        class="w-full text-left px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-100">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>