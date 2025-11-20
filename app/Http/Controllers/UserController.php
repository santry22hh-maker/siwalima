<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

// Hapus 'use Hash', 'use Rules', 'use Role' karena tidak diperlukan lagi

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua Pengguna (DataTables).
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // --- PERBAIKAN: Filter hanya role 'Pengguna' ---
            $data = User::where('id', '!=', Auth::id())
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'Pengguna'); // <-- HANYA AMBIL ROLE 'PENGGUNA'
                })
                ->orderByDesc('created_at')
                ->with('roles');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('roles', function ($row) {
                    return $row->getRoleNames()->implode(', ');
                })
                ->addColumn('action', function ($row) {
                    /** @var \App\Models\User $user */
                    $user = Auth::user();

                    // Tampilkan Hapus untuk Admin ATAU Penelaah
                    if ($user->hasRole(['Admin', 'Penelaah'])) {
                        $deleteUrl = route('users.destroy', $row->id);

                        $btn = '<form action="'.$deleteUrl.'" method="POST" class="inline" onsubmit="return confirm(\'Anda yakin ingin menghapus pengguna ini?\');">'
                            .csrf_field().method_field('DELETE')
                            .'<button type="submit" class="px-2 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600" style="background-color: #ef4444; color: white; padding: 4px 8px; border-radius: 0.25rem; font-size: 0.75rem; border: none; cursor: pointer;">Hapus</button>'
                            .'</form>';

                        return $btn;
                    }

                    return '-';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users.index');
    }

    /**
     * Menghapus Pengguna.
     */
    public function destroy(User $user)
    {
        // Pastikan Admin/Penelaah tidak menghapus dirinya sendiri
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        // Jangan izinkan Penelaah menghapus Admin
        if (Auth::user()->hasRole('Penelaah') && $user->hasRole('Admin')) {
            return redirect()->route('users.index')->with('error', 'Penelaah tidak diizinkan menghapus Admin.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    // Method create, store, edit, dan update telah dihapus
}
