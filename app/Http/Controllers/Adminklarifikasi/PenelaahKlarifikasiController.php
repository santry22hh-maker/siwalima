<?php

namespace App\Http\Controllers\Adminklarifikasi;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class PenelaahKlarifikasiController extends Controller
{
    private $roleName = 'Penelaah Klarifikasi';

    /**
     * Menampilkan halaman manajemen penelaah.
     */
    public function index()
    {
        // Otorisasi: Cek Permission 'access klarifikasi backend'
        // Ini akan mengizinkan Admin, Admin IGT, dan Admin Klarifikasi (jika mereka punya izin ini)
        if (! Auth::user()->can('access klarifikasi backend')) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        return view('adminklarifikasi.penelaah.index');
    }

    /**
     * Melayani data JSON untuk DataTables.
     */
    public function getData(Request $request)
    {
        // Ambil semua user, KECUALI super admin (jika ada) dan diri sendiri
        $excludedRoles = ['Admin', 'Admin IGT', 'Admin Klarifikasi'];

        $query = User::with('roles')
            ->where('id', '!=', Auth::id())
            ->whereDoesntHave('roles', fn ($q) => $q->whereIn('name', $excludedRoles));

        return DataTables::of($query)
            ->addColumn('status_penelaah', function ($user) {
                $penelaahRoles = ['Penelaah IGT', 'Penelaah Klarifikasi'];

                $userPenelaahRoles = $user->roles->pluck('name')->intersect($penelaahRoles);

                if ($userPenelaahRoles->isNotEmpty()) {
                    $roles = $userPenelaahRoles->join(', ');

                    return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">'
                         .$roles.
                    '</span>';
                }

                return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Non-Aktif</span>';
            })
            ->addColumn('action', function ($user) {
                $csrf = csrf_field();
                $action = '';
                $btnClass = '';
                $btnIcon = '';
                $btnText = '';

                if ($user->hasRole($this->roleName)) {
                    // Jika sudah jadi penelaah, tampilkan tombol Hapus
                    $action = 'remove';
                    $btnClass = 'bg-red-600 hover:bg-red-700';
                    $btnIcon = 'fas fa-user-minus';
                    $btnText = 'Hapus Role';
                } else {
                    // Jika bukan, tampilkan tombol Tugaskan
                    $action = 'assign';
                    $btnClass = 'bg-green-600 hover:bg-green-700';
                    $btnIcon = 'fas fa-user-plus';
                    $btnText = 'Tugaskan';
                }

                $route = route('adminklarifikasi.penelaah.toggleRole', $user->id);

                $form = '<form action="'.$route.'" method="POST" onsubmit="return confirm(\'Anda yakin?\');">';
                $form .= $csrf;
                $form .= '<input type="hidden" name="action" value="'.$action.'">';
                $form .= '<button type="submit" class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-white shadow-sm transition '.$btnClass.'">';
                $form .= '<i class="'.$btnIcon.'"></i> '.$btnText;
                $form .= '</button></form>';

                return $form;
            })
            ->rawColumns(['status_penelaah', 'action'])
            ->make(true);
    }

    /**
     * Menambah/Menghapus role Penelaah Klarifikasi dari user.
     */
    public function toggleRole(Request $request, User $user)
    {
        // Hanya Admin IGT atau Admin Klarifikasi yang bisa mengakses
        if (! Auth::user()->hasAnyRole(['Admin IGT', 'Admin Klarifikasi'])) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $validated = $request->validate([
            'action' => 'required|in:assign,remove',
        ]);

        if ($validated['action'] === 'assign') {
            $user->assignRole($this->roleName);

            return redirect()->route('adminklarifikasi.penelaah.index')
                ->with('success', $user->name.' berhasil ditugaskan sebagai Penelaah Klarifikasi.');
        }

        if ($validated['action'] === 'remove') {
            $user->removeRole($this->roleName);

            return redirect()->route('adminklarifikasi.penelaah.index')
                ->with('success', 'Role Penelaah Klarifikasi untuk '.$user->name.' berhasil dihapus.');
        }

        return redirect()->route('adminklarifikasi.penelaah.index')
            ->with('error', 'Aksi tidak valid.');
    }

    public function create()
    {
        if (! Auth::user()->hasAnyRole(['Admin IGT', 'Admin Klarifikasi'])) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        return view('adminklarifikasi.penelaah.create');
    }

    /**
     * Menyimpan user baru dan langsung menjadikannya Penelaah.
     */
    public function store(Request $request)
    {
        // Otorisasi
        if (! Auth::user()->hasAnyRole(['Admin IGT', 'Admin Klarifikasi'])) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_target' => 'required|in:Penelaah IGT,Penelaah Klarifikasi', // Admin memilih jenis penelaah
        ]);

        // 1. Buat User Baru
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // 2. Berikan Role Penelaah
        $user->assignRole('Penelaah'); // Role dasar untuk login backend
        $user->assignRole($validated['role_target']); // Role spesifik

        return redirect()->route('adminklarifikasi.penelaah.index')
            ->with('success', 'Penelaah baru ('.$user->name.') berhasil dibuat dan ditugaskan.');
    }
}
