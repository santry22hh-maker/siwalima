<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JigDashboardController extends Controller
{
    /**
     * Menampilkan dashboard untuk pengguna dengan peran 'jig'.
     */
    public function index()
    {
        return view('jig.index');
    }
    public function showmap()
    {
        return view('jig.showmap');
    }
}
