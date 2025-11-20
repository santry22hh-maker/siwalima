<?php

namespace App\Http\Controllers;

use App\Models\Carousel;
use App\Models\Newsfeed;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Data Carousel (Khusus Landing Page)
        $carousels = Carousel::where('is_active', true)->latest()->get();
        $news = Newsfeed::select('id', 'title', 'slug', 'image_path', 'published_at') // <--- Ambil yang perlu saja
            ->latest('published_at')
            ->take(3)
            ->get();

        // 3. Kirim ke View 'welcome' (Landing Page)
        return view('welcome', compact('carousels', 'news'));
    }

    public function indexdashboard(Request $request)
    {
        // 1. Ambil Data Carousel (Khusus Landing Page)
        $carousels = Carousel::where('is_active', true)->latest()->get();
        $news = Newsfeed::select('id', 'title', 'slug', 'image_path', 'published_at') // <--- Ambil yang perlu saja
            ->latest('published_at')
            ->take(3)
            ->get();

        // 3. Kirim ke View 'welcome' (Landing Page)
        return view('dashboard', compact('carousels', 'news'));
    }
}
