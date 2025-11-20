<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class PenelaahController extends Controller
{
    // Kita tidak lagi menggunakan satu $roleName statis karena admin bisa memilih role
    // private $roleName = 'Penelaah IGT';

    /**
     * Menampilkan halaman manajemen penelaah.
     */
    public function index()
    {
        // Otorisasi: Hanya Admin IGT atau Admin Klarifikasi yang bisa akses
        if (! Auth::user()->hasAnyRole(['Admin IGT', 'Admin Klarifikasi'])) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        return view('penelaah.index');
    }

    /**
     * Melayani data JSON untuk DataTables.
     */
    public function getData(Request $request)
    {
        // Ambil semua user, KECUALI super admin (Admin IGT/Klarifikasi) dan diri sendiri
        // Kita ingin menampilkan 'Pengguna' biasa DAN 'Penelaah'
        $excludedRoles = ['Admin', 'Admin IGT', 'Admin Klarifikasi'];

        $query = User::with('roles')
            ->where('id', '!=', Auth::id())
            ->whereDoesntHave('roles', fn ($q) => $q->whereIn('name', $excludedRoles));

        return DataTables::of($query)
            ->addColumn('status_penelaah', function ($user) {
                $penelaahRoles = ['Penelaah IGT', 'Penelaah Klarifikasi'];
                $userPenelaahRoles = $user->roles->pluck('name')->intersect($penelaahRoles);

                if ($userPenelaahRoles->isNotEmpty()) {
                    // Tampilkan badge untuk setiap role penelaah yang dimiliki
                    return $userPenelaahRoles->map(function ($role) {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mr-1">'.$role.'</span>';
                    })->join(' ');
                }

                return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Non-Aktif (Pengguna)</span>';
            })
            ->addColumn('action', function ($user) {
                $csrf = csrf_field();

                // Tentukan role mana yang relevan untuk Admin yang sedang login
                $targetRole = '';
                if (Auth::user()->hasRole('Admin IGT')) {
                    $targetRole = 'Penelaah IGT';
                } elseif (Auth::user()->hasRole('Admin Klarifikasi')) {
                    $targetRole = 'Penelaah Klarifikasi';
                }

                // Cek apakah user sudah punya role target tersebut
                $hasTargetRole = $user->hasRole($targetRole);

                if ($hasTargetRole) {
                    // Tombol Hapus Role
                    $action = 'remove';
                    $btnClass = 'bg-red-600 hover:bg-red-700';
                    $btnIcon = 'fas fa-user-minus';
                    $btnText = 'Hapus Role';
                } else {
                    // Tombol Tugaskan
                    $action = 'assign';
                    $btnClass = 'bg-green-600 hover:bg-green-700';
                    $btnIcon = 'fas fa-user-plus';
                    $btnText = 'Tugaskan';
                }

                $route = route('penelaah.toggleRole', $user->id);

                $form = '<form action="'.$route.'" method="POST" onsubmit="return confirm(\'Anda yakin?\');">';
                $form .= $csrf;
                $form .= '<input type="hidden" name="action" value="'.$action.'">';
                $form .= '<input type="hidden" name="role_target" value="'.$targetRole.'">'; // Kirim role target
                $form .= '<button type="submit" class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-white shadow-sm transition '.$btnClass.'">';
                $form .= '<i class="'.$btnIcon.'"></i> '.$btnText;
                $form .= '</button></form>';

                return $form;
            })
            ->rawColumns(['status_penelaah', 'action'])
            ->make(true);
    }

    /**
     * Menambah/Menghapus role Penelaah dari user.
     */
    public function toggleRole(Request $request, User $user)
    {
        // Otorisasi
        if (! Auth::user()->hasAnyRole(['Admin IGT', 'Admin Klarifikasi'])) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $validated = $request->validate([
            'action' => 'required|in:assign,remove',
            'role_target' => 'required|in:Penelaah IGT,Penelaah Klarifikasi', // Validasi role
        ]);

        $targetRole = $validated['role_target'];

        // --- LOGIKA ASSIGN (TUGASKAN) ---
        if ($validated['action'] === 'assign') {

            // 1. Jika user masih 'Pengguna', cabut role itu
            if ($user->hasRole('Pengguna')) {
                $user->removeRole('Pengguna');
            }

            // 2. Berikan role Penelaah Umum (jika belum punya) agar bisa login backend
            if (! $user->hasRole('Penelaah')) {
                $user->assignRole('Penelaah');
            }

            // 3. Berikan role spesifik (IGT/Klarifikasi)
            $user->assignRole($targetRole);

            return redirect()->route('penelaah.index')
                ->with('success', $user->name.' berhasil ditugaskan sebagai '.$targetRole);
        }

        // --- LOGIKA REMOVE (HAPUS) ---
        if ($validated['action'] === 'remove') {

            // 1. Cabut role spesifik
            $user->removeRole($targetRole);

            // 2. Cek apakah user masih punya jabatan penelaah LAIN?
            // (Misal: dia Penelaah IGT + Penelaah Klarifikasi. Jika IGT dihapus, dia masih Penelaah Klarifikasi)
            $hasOtherPenelaahRole = $user->hasAnyRole(['Penelaah IGT', 'Penelaah Klarifikasi']);

            if (! $hasOtherPenelaahRole) {
                // Jika sudah tidak punya jabatan apapun:
                // Cabut role induk 'Penelaah'
                $user->removeRole('Penelaah');

                // Kembalikan jadi 'Pengguna' biasa
                $user->assignRole('Pengguna');
            }

            return redirect()->route('penelaah.index')
                ->with('success', 'Role '.$targetRole.' untuk '.$user->name.' berhasil dihapus.');
        }

        return redirect()->route('penelaah.index')
            ->with('error', 'Aksi tidak valid.');
    }

    public function create()
    {
        if (! Auth::user()->hasAnyRole(['Admin IGT', 'Admin Klarifikasi'])) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        return view('penelaah.create');
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

        return redirect()->route('penelaah.index')
            ->with('success', 'Penelaah baru ('.$user->name.') berhasil dibuat dan ditugaskan.');
    }
}
