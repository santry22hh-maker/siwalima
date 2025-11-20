<?php

namespace App\Http\Controllers;

// <-- Ganti ke model Anda
use App\Models\DataIgt; // <-- Impor model IGT
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class DataIgtController extends Controller
{
    // ... (method index, dll.) ...
    public function index(Request $request)
    {
        if ($request->ajax()) {

            // Query sederhana, tanpa filter cakupan
            $data = DataIgt::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="igt-checkbox rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900" data-id="'.$row->id.'">';
                })
                ->addColumn('action', function ($row) {
                    /** @var \App\Models\User $user */
                    $user = Auth::user();
                    if ($user->hasRole(['Admin IGT'])) {
                        $editUrl = route('daftarigt.edit', $row->id);
                        $deleteUrl = route('daftarigt.destroy', $row->id);

                        $btn = '<a href="'.$editUrl.'" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>';
                        $btn .= '<form action="'.$deleteUrl.'" method="POST" class="inline" onsubmit="return confirm(\'Anda yakin ingin menghapus data ini?\');">';
                        $btn .= csrf_field();
                        $btn .= method_field('DELETE');
                        $btn .= '<button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>';
                        $btn .= '</form>';

                        return $btn;
                    }

                    return '-';
                })
                ->rawColumns(['action', 'checkbox'])
                ->make(true);
        }

        return view('daftarigt.index');
    }

    public function create()
    {
        // Hanya mengembalikan view formulir
        return view('daftarigt.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi data
        $validated = $request->validate([
            'jenis_data' => 'required|string|max:255',
            'periode_update' => 'nullable|string|max:100',
            'format_data' => 'required|string|max:100',
        ]);

        // 2. Buat data baru
        DataIgt::create($validated); // Pastikan model Anda 'DataIgt'

        // 3. Redirect kembali ke halaman daftar
        return redirect()->route('daftarigt.index')
            ->with('success', 'Data IGT baru berhasil ditambahkan.');
    }

    public function edit(DataIgt $daftarigt) // <-- Laravel otomatis mencari DataIgt dari ID
    {
        // Rute Anda menggunakan {daftarigt}, jadi nama variabelnya harus $daftarigt
        // agar route-model binding berfungsi.

        return view('daftarigt.edit', [
            'igt' => $daftarigt,
        ]);
    }

    /**
     * Memperbarui data IGT di database.
     */
    public function update(Request $request, DataIgt $daftarigt)
    {
        // 1. Validasi data (sama seperti store)
        $validated = $request->validate([
            'jenis_data' => 'required|string|max:255',
            'periode_update' => 'nullable|string|max:100',
            'format_data' => 'required|string|max:100',
        ]);

        // 2. Update data
        $daftarigt->update($validated);

        // 3. Redirect kembali ke halaman daftar
        return redirect()->route('daftarigt.index')
            ->with('success', 'Data IGT berhasil diperbarui.');
    }
}
