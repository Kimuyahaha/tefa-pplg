<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __(' Perbarui informasi akun Anda.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="nama" value="Nama" />
            <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full"
                :value="old('nama', $user->nama)" required autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('nama')" />
        </div>

        <div>
            <x-input-label for="kelas" value="Kelas" />
            <x-text-input id="kelas" name="kelas" type="text" class="mt-1 block w-full"
                :value="old('kelas', $user->kelas)" />
            <x-input-error class="mt-2" :messages="$errors->get('kelas')" />
        </div>

        <div>
            <x-input-label for="jurusan" value="Jurusan" />
            <x-text-input id="jurusan" name="jurusan" type="text" class="mt-1 block w-full"
                :value="old('jurusan', $user->jurusan)" />
            <x-input-error class="mt-2" :messages="$errors->get('jurusan')" />
        </div>

        <div>
            <x-input-label for="no_hp" value="No HP" />
            <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full"
                :value="old('no_hp', $user->no_hp)" />
            <x-input-error class="mt-2" :messages="$errors->get('no_hp')" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                :value="old('email', $user->email)" required />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Simpan</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">
                    Tersimpan.
                </p>
            @endif
        </div>
    </form>
</section>