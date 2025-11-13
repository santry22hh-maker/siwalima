<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Yajra\DataTables\DataTables;

class PenelaahController extends Controller
{
    /**
     * Menampilkan daftar Penelaah (DataTables).
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Ambil semua pengguna yang memiliki peran 'Penelaah'
            $data = User::role('Penelaah');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('penelaah.edit', $row->id);
                    $deleteUrl = route('penelaah.destroy', $row->id);

                    $btn = '<a href="' . $editUrl . '" class="px-2 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600 mr-2">Edit</a>';
                    $btn .= '<form action="' . $deleteUrl . '" method="POST" class="inline" onsubmit="return confirm(\'Anda yakin ingin menghapus Penelaah ini?\');">'
                        . csrf_field() . method_field('DELETE')
                        . '<button type="submit" class="px-2 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600">Hapus</button>'
                        . '</form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('penelaah.index');
    }

    /**
     * Menampilkan form untuk membuat Penelaah baru.
     */
    public function create()
    {
        return view('penelaah.create');
    }

    /**
     * Menyimpan Penelaah baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Tetapkan peran 'Penelaah'
        $user->assignRole('Penelaah');

        return redirect()->route('penelaah.index')->with('success', 'Penelaah baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit Penelaah.
     */
    public function edit(User $penelaah) // Laravel akan otomatis mencari User
    {
        // $penelaah adalah variabel dari {penelaah} di rute resource
        return view('penelaah.edit', compact('penelaah'));
    }

    /**
     * Mengupdate data Penelaah.
     */
    public function update(Request $request, User $penelaah)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class . ',email,' . $penelaah->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $penelaah->update($data);

        return redirect()->route('penelaah.index')->with('success', 'Data Penelaah berhasil diperbarui.');
    }

    /**
     * Menghapus Penelaah.
     */
    public function destroy(User $penelaah)
    {
        // Hapus pengguna
        $penelaah->delete();

        return redirect()->route('penelaah.index')->with('success', 'Penelaah berhasil dihapus.');
    }
}
