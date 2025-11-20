<?php

namespace App\Http\Controllers;

use App\Models\Newsfeed;

class NewsController extends Controller
{
    public function index()
    {
        // Ambil semua berita, urutkan terbaru, bagi 9 per halaman
        $news = Newsfeed::latest('published_at')->paginate(9);

        return view('news.index', compact('news'));
    }

    // Nanti bisa tambah method show() untuk detail berita
    public function show($slug)
    {
        $newsItem = Newsfeed::where('slug', $slug)->firstOrFail();

        return view('news.show', compact('newsItem'));
    }
}
