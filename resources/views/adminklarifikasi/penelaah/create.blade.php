<x-klarifikasi-layout>
    <div class="max-w-2xl mx-auto px-2 mb-4">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
            <div class="mb-6 border-b pb-4 dark:border-gray-700">
                <h3 class="text-xl font-medium text-gray-800 dark:text-white/90">
                    Tambah Penelaah Baru
                </h3>
            </div>

            <form action="{{ route('penelaah.store') }}" method="POST">
                @csrf

                {{-- Nama --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                {{-- Password --}}
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Konfirmasi
                            Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 col-span-2" />
                </div>

                {{-- Pilihan Role --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Tugaskan
                        Sebagai</label>
                    <select name="role_target"
                        class="w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                        @if (Auth::user()->hasRole('Admin IGT') || Auth::user()->hasRole('Admin'))
                            <option value="Penelaah IGT">Penelaah IGT</option>
                        @endif
                        @if (Auth::user()->hasRole('Admin Klarifikasi') || Auth::user()->hasRole('Admin'))
                            <option value="Penelaah Klarifikasi">Penelaah Klarifikasi</option>
                        @endif
                    </select>
                    <x-input-error :messages="$errors->get('role_target')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('penelaah.index') }}"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</a>
                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700">Simpan &
                        Tugaskan</button>
                </div>
            </form>
        </div>
    </div>
</x-klarifikasi-layout>
