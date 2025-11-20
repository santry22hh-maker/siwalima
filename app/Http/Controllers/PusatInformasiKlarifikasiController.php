<?php

namespace App\Http\Controllers;

class PusatInformasiKlarifikasiController extends Controller
{
    /**
     * Menampilkan halaman Panduan Layanan.
     */
    public function panduan()
    {
        return view('pusatinformasi.panduan');
    }

    /**
     * Menampilkan halaman FAQ.
     */
    public function faq()
    {
        return view('pusatinformasi.faq');
    }

    /**
     * Menampilkan halaman Dasar Hukum & SOP.
     */
    public function sop()
    {
        return view('pusatinformasi.sop');
    }

    /**
     * Menampilkan halaman Kontak Layanan.
     */
    public function kontak()
    {
        return view('pusatinformasi.kontak');
    }
}
