<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

// -----------------------------
class DocumentController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query
        $query = Document::query();

        // Logika Pencarian (Search)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhere('type', 'like', '%'.$search.'%');
            });
        }

        // Ambil data, urutkan terbaru, paginate 6 item per halaman
        $documents = $query->latest()->paginate(6);

        return view('documents.index', compact('documents'));
    }
}
