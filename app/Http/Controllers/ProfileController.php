<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule; // Pastikan Model User di-import

class ProfileController extends Controller
{
    /**
     * Menampilkan form edit profil.
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Memperbarui profil pengguna.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Validasi
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id), // Cek email unik, abaikan email user saat ini
            ],
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Opsional, maks 2MB
        ]);

        $dataToUpdate = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        // 2. Handle File Upload
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            // Simpan avatar baru
            $path = $request->file('avatar')->store('avatars', 'public');
            $dataToUpdate['avatar_path'] = $path;
        }

        // 3. Update Database
        $user->update($dataToUpdate);

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
