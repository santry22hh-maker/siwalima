<?php

namespace App\View\Components\klarifikasilayouts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class sidebarjig extends Component
{
    
    public $links;

    public function __construct()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // -----------------------------------------------
        // --- 1. LOGIKA SUBMENU LAYANAN PENGADUAN ---
        // -----------------------------------------------
        $layananPengaduanSubmenu = [];
        $pengaduanActiveRoutes = [];

        if ($user) {
            // --- Staf (Admin & Penelaah) ---
            if ($user->hasRole(['Admin', 'Penelaah'])) {
                $layananPengaduanSubmenu[] = [
                    'name' => 'Daftar Pengaduan',
                    'route' => 'pengaduan.list',
                    'is_active' => request()->routeIs(['pengaduan.list', 'pengaduan.show']),
                ];
                $pengaduanActiveRoutes = ['pengaduan.list', 'pengaduan.show'];
            }
            // --- Pengguna ---
            if ($user->hasRole('Pengguna')) {
                $layananPengaduanSubmenu[] = [
                    'name' => 'Pengaduan Saya',
                    'route' => 'pengaduan.saya',
                    'is_active' => request()->routeIs('pengaduan.saya'),
                ];
                $layananPengaduanSubmenu[] = [
                    'name' => 'Layanan Pengaduan',
                    'route' => 'pengaduan.index',
                    'is_active' => request()->routeIs('pengaduan.index'),
                ];
                $pengaduanActiveRoutes = array_merge($pengaduanActiveRoutes, ['pengaduan.saya', 'pengaduan.index']);
            }
        }

        // -----------------------------------------------
        // --- 2. LOGIKA SUBMENU PELAYANAN DATA IGT ---
        // -----------------------------------------------
        $igtSubmenu = [];
        $igtSubmenuRoutes = [];
        $igtSubmenu[] = [
            'name' => 'Katalog Data IGT',
            'route' => 'daftarigt.index',
            'is_active' => request()->routeIs('daftarigt.index'),
        ];
        $igtSubmenuRoutes[] = 'daftarigt.index';
        if ($user) {
            if ($user->hasRole('Pengguna')) {
                $igtSubmenu[] = [
                    'name' => 'Permohonan Saya',
                    'route' => 'permohonanspasial.saya',
                    'is_active' => request()->routeIs('permohonanspasial.saya'),
                ];
                $igtSubmenu[] = [
                    'name' => 'Laporan Penggunaan',
                    'route' => 'laporanpenggunaan.index',
                    'is_active' => request()->routeIs('laporanpenggunaan.index'),
                ];
                $igtSubmenuRoutes = array_merge($igtSubmenuRoutes, ['permohonanspasial.saya', 'laporanpenggunaan.index']);
            }
            if ($user->hasRole(['Admin', 'Penelaah'])) {
                $igtSubmenu[] = [
                    'name' => 'Daftar Permohonan',
                    'route' => 'permohonanspasial.index',
                    'is_active' => request()->routeIs('permohonanspasial.index'),
                ];
                $igtSubmenu[] = [
                    'name' => 'Review Laporan',
                    'route' => 'laporanpenggunaan.review',
                    'is_active' => request()->routeIs('laporanpenggunaan.review'),
                ];
                $igtSubmenu[] = [
                    'name' => 'Monitoring Survey',
                    'route' => 'surveypenggunaan.index',
                    'is_active' => request()->routeIs('surveypenggunaan.index'),
                ];
                $igtSubmenuRoutes = array_merge($igtSubmenuRoutes, ['permohonanspasial.index', 'laporanpenggunaan.review', 'surveypenggunaan.index']);
            }
        }

        // -----------------------------------------------
        // --- 3. LOGIKA SUBMENU STATISTIK ---
        // -----------------------------------------------
        $statistikSubmenu = [];
        $statistikActiveRoutes = [];
        if ($user && $user->hasRole(['Admin', 'Penelaah'])) {
            $statistikSubmenu[] = [
                'name' => 'Statistik Layanan IGT',
                'route' => 'statistik.igt',
                'is_active' => request()->routeIs('statistik.igt'),
            ];
            $statistikSubmenu[] = [
                'name' => 'Statistik Survey',
                'route' => 'statistik.survey',
                'is_active' => request()->routeIs('statistik.survey'),
            ];
            $statistikSubmenu[] = [
                'name' => 'Statistik Pengaduan',
                'route' => 'statistik.pengaduan',
                'is_active' => request()->routeIs('statistik.pengaduan'),
            ];
            $statistikActiveRoutes = ['statistik.igt', 'statistik.survey', 'statistik.pengaduan'];
        }

        // -----------------------------------------------
        // --- 4. (DIPERBARUI) LOGIKA SUBMENU MANAJEMEN AKUN ---
        // -----------------------------------------------
        $manajemenAkunSubmenu = []; // <-- Nama variabel diubah
        $manajemenAkunActiveRoutes = []; // <-- Nama variabel diubah

        if ($user && $user->hasRole(['Admin', 'Penelaah'])) {
            $manajemenAkunSubmenu[] = [
                'name' => 'Semua Akun', // <-- NAMA DIUBAH
                'route' => 'users.index',
                'is_active' => request()->routeIs('users.*'),
            ];
            $manajemenAkunActiveRoutes[] = 'users.*';
        }
        if ($user && $user->hasRole('Admin')) {
            $manajemenAkunSubmenu[] = [
                'name' => 'Akun Penelaah', // <-- NAMA DIUBAH
                'route' => 'penelaah.index',
                'is_active' => request()->routeIs('penelaah.*'),
            ];
            $manajemenAkunActiveRoutes[] = 'penelaah.*';
        }

        // -----------------------------------------------
        // --- 5. ARRAY LINKS UTAMA (DIPERBARUI) ---
        // -----------------------------------------------
        $links = [
            [
                'name' => 'Home',
                'route' => 'dashboard',
                'icon' => 'fas fa-home fa-lg',
                'is_active' => request()->routeIs('dashboard'),
            ],
            [
                'name' => 'Peta Interaktif',
                'route' => 'jig.showmap',
                'icon' => 'fas fa-pen-square fa-lg',
                'is_active' => request()->routeIs('jig.showmap'),
            ],
            [
                'name' => 'Pelayanan Data IGT',
                'icon' => 'fas fa-layer-group fa-lg',
                'is_active' => request()->routeIs($igtSubmenuRoutes),
                'is_dropdown' => true,
                'submenu' => $igtSubmenu,
            ],
            [
                'name' => 'Layanan Pengaduan',
                'icon' => 'fas fa-envelope-open-text fa-lg',
                'is_active' => request()->routeIs($pengaduanActiveRoutes),
                'is_dropdown' => true,
                'submenu' => $layananPengaduanSubmenu,
            ],
        ];

        // Tambahkan menu Manajemen Akun (jika tidak kosong)
        if (!empty($manajemenAkunSubmenu)) {
            $links[] = [
                'name' => 'Manajemen Akun', // <-- NAMA INDUK DIUBAH
                'icon' => 'fas fa-users-cog fa-lg',
                'is_active' => request()->routeIs($manajemenAkunActiveRoutes),
                'is_dropdown' => true,
                'submenu' => $manajemenAkunSubmenu, // <-- Submenu yang sudah diperbarui
            ];
        }

        // Tambahkan menu Statistik (jika tidak kosong)
        if (!empty($statistikSubmenu)) {
            $links[] = [
                'name' => 'Statistik',
                'icon' => 'fas fa-chart-bar fa-lg',
                'is_active' => request()->routeIs($statistikActiveRoutes),
                'is_dropdown' => true,
                'submenu' => $statistikSubmenu,
            ];
        }

        $this->links = $links;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.klarifikasilayouts.sidebarjig');
    }
}
