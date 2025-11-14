<x-jig-layout>
    <div class="px-2 mb-4">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
            <h3
                class="text-base font-medium text-gray-800 dark:text-white/90 border-b border-gray-200 dark:border-gray-800 pb-3 mb-6">
                Tambah Penelaah Baru
            </h3>

            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Oops! Terjadi kesalahan:</strong>
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('penelaah.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    {{-- Nama --}}
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                            :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                            required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                            class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                </div>

                {{-- Tombol Aksi --}}
                <div class="flex items-center justify-end mt-6 pt-4 border-t dark:border-gray-700">
                    <a href="{{ route('penelaah.index') }}"
                        class="text-sm text-gray-600 hover:text-gray-900 mr-4 dark:text-gray-400 dark:hover:text-white">
                        Batal
                    </a>
                    <x-primary-button>
                        Simpan Penelaah
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-jig-layout>
