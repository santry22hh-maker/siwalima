<x-klarifikasi-layout> {{-- Menggunakan layout yang sama dengan halaman lain --}}

    {{-- Header Halaman --}}
    <div class="px-2 mb-4">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4">
            <h3 class="text-xl font-medium text-gray-800 dark:text-white/90">
                Edit Profil
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Perbarui informasi akun dan foto avatar Anda.
            </p>
        </div>
    </div>

    {{-- Konten Form --}}
    <div class="px-2 mb-4">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">

            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            {{-- Daftar Error Validasi --}}
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                    <p class="font-bold">Oops! Ada yang salah:</p>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form harus menggunakan multipart/form-data untuk upload file --}}
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH') {{-- Gunakan PATCH untuk update --}}

                <div class="space-y-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                            Foto Avatar
                        </label>
                        <div class="mt-1 flex items-center space-x-4">
                            <span
                                class="inline-block h-20 w-20 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700">
                                {{-- Tampilkan avatar saat ini atau default --}}
                                @if ($user->avatar_path)
                                    <img class="h-full w-full object-cover"
                                        src="{{ asset('storage/' . $user->avatar_path) }}" alt="Avatar saat ini">
                                @else
                                    {{-- Icon SVG placeholder --}}
                                    <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                @endif
                            </span>
                            <input type="file" name="avatar" id="avatar" accept="image/png, image/jpeg"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300 dark:hover:file:bg-gray-600">
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPG, PNG. Maks: 2MB.</p>
                        <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
                    </div>

                    <hr class="dark:border-gray-700">

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                            Nama Lengkap
                        </label>
                        {{-- Asumsi Anda memiliki komponen x-text-input --}}
                        <x-text-input id="name" type="text" name="name" :value="old('name', $user->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                            Alamat Email
                        </label>
                        <x-text-input id="email" type="email" name="email" :value="old('email', $user->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="pt-5 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Simpan Perubahan
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-klarifikasi-layout>
