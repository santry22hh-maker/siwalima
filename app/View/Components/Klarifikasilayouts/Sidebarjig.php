<?php

namespace App\View\Components\Klarifikasilayouts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Sidebarjig extends Component
{
    public $links;

    public function __construct()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Pastikan $links dideklarasi dulu untuk mencegah error
        $links = [];

        // ============================================================
        // 1. SUBMENU LAYANAN PENGADUAN
        // ============================================================
        $layananPengaduanSubmenu = [];
        $pengaduanActiveRoutes = [];

        if ($user) {
            if ($user->hasRole(['Admin IGT', 'Penelaah IGT', 'Admin Klarifikasi', 'Penelaah Klarifikasi'])) {
                $layananPengaduanSubmenu[] = [
                    'name' => 'Daftar Pengaduan',
                    'route' => 'pengaduan.list',
                    'is_active' => request()->routeIs(['pengaduan.list', 'pengaduan.show']),
                ];
                $pengaduanActiveRoutes = ['pengaduan.list', 'pengaduan.show'];
            }

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
                $pengaduanActiveRoutes = array_merge($pengaduanActiveRoutes, [
                    'pengaduan.saya', 'pengaduan.index',
                ]);
            }
        }

        // ============================================================
        // 2. SUBMENU PELAYANAN DATA IGT
        // ============================================================
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
                    'name' => 'Permohonan IGT Saya',
                    'route' => 'permohonanspasial.saya',
                    'is_active' => request()->routeIs('permohonanspasial.saya'),
                ];
                $igtSubmenu[] = [
                    'name' => 'Laporan Penggunaan IGT',
                    'route' => 'laporanpenggunaan.index',
                    'is_active' => request()->routeIs('laporanpenggunaan.index'),
                ];
                $igtSubmenuRoutes = array_merge($igtSubmenuRoutes, [
                    'permohonanspasial.saya', 'laporanpenggunaan.index',
                ]);
            }

            if ($user->hasRole(['Admin IGT', 'Penelaah IGT', 'Admin Klarifikasi', 'Penelaah Klarifikasi'])) {
                $igtSubmenu[] = [
                    'name' => 'Daftar Permohonan IGT',
                    'route' => 'permohonanspasial.index',
                    'is_active' => request()->routeIs('permohonanspasial.index'),
                ];
                $igtSubmenu[] = [
                    'name' => 'Review Laporan IGT',
                    'route' => 'laporanpenggunaan.review',
                    'is_active' => request()->routeIs('laporanpenggunaan.review'),
                ];
                $igtSubmenu[] = [
                    'name' => 'Monitoring Survey IGT',
                    'route' => 'surveypenggunaan.index',
                    'is_active' => request()->routeIs('surveypenggunaan.index'),
                ];
                $igtSubmenuRoutes = array_merge($igtSubmenuRoutes, [
                    'permohonanspasial.index',
                    'laporanpenggunaan.review',
                    'surveypenggunaan.index',
                ]);
            }
        }

        // ============================================================
        // 3. SUBMENU STATISTIK
        // ============================================================
        $statistikSubmenu = [];
        $statistikActiveRoutes = [];

        if ($user && $user->hasRole(['Admin IGT', 'Penelaah IGT', 'Admin Klarifikasi', 'Penelaah Klarifikasi'])) {
            $statistikSubmenu[] = [
                'name' => 'Statistik Layanan IGT',
                'route' => 'statistik.igt',
                'is_active' => request()->routeIs('statistik.igt'),
            ];
            $statistikSubmenu[] = [
                'name' => 'Statistik Survey IGT',
                'route' => 'statistik.survey',
                'is_active' => request()->routeIs('statistik.survey'),
            ];
            $statistikSubmenu[] = [
                'name' => 'Statistik Pengaduan IGT',
                'route' => 'statistik.pengaduan',
                'is_active' => request()->routeIs('statistik.pengaduan'),
            ];

            $statistikActiveRoutes = [
                'statistik.igt',
                'statistik.survey',
                'statistik.pengaduan',
            ];
        }

        // ============================================================
        // 4. SUBMENU MANAJEMEN AKUN
        // ============================================================
        $manajemenAkunSubmenu = [];
        $manajemenAkunActiveRoutes = [];

        $allowedRoles = ['Admin IGT', 'Penelaah IGT', 'Admin Klarifikasi', 'Penelaah Klarifikasi'];

        if ($user && $user->hasAnyRole($allowedRoles)) {
            $manajemenAkunSubmenu[] = [
                'name' => 'Semua Akun Pengguna',
                'route' => 'users.index',
                'is_active' => request()->routeIs('users.*'),
            ];
            $manajemenAkunActiveRoutes[] = 'users.*';
        }

        if ($user && $user->hasAnyRole($allowedRoles)) {
            $manajemenAkunSubmenu[] = [
                'name' => 'Akun Penelaah',
                'route' => 'penelaah.index',
                'is_active' => request()->routeIs('penelaah.*'),
            ];
            $manajemenAkunActiveRoutes[] = 'penelaah.*';
        }

        // ============================================================
        // 5. LINKS UTAMA
        // ============================================================
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
        // ============================================================
        // 6. TAMBAH MENU REKAP SURVEY (ADMIN/PENELAAH)
        // ============================================================
        if ($user && $user->hasRole(['Admin IGT', 'Penelaah IGT', 'Admin Klarifikasi', 'Penelaah Klarifikasi'])) {
            $links[] = [
                'name' => 'Rekapitulasi Survey',
                'icon' => 'fas fa-chart-line fa-lg',
                'is_active' => request()->routeIs('survey.rekap.igt'),
                'is_dropdown' => false,
                'submenu' => [],
                'route' => 'survey.rekap.igt',
            ];
        }

        // Statistik
        if (! empty($statistikSubmenu)) {
            $links[] = [
                'name' => 'Statistik',
                'icon' => 'fas fa-chart-bar fa-lg',
                'is_active' => request()->routeIs($statistikActiveRoutes),
                'is_dropdown' => true,
                'submenu' => $statistikSubmenu,
            ];
        }
        // Manajemen Akun
        if (! empty($manajemenAkunSubmenu)) {
            $links[] = [
                'name' => 'Manajemen Akun IGT',
                'icon' => 'fas fa-users-cog fa-lg',
                'is_active' => request()->routeIs($manajemenAkunActiveRoutes),
                'is_dropdown' => true,
                'submenu' => $manajemenAkunSubmenu,
            ];
        }

        $this->links = $links;
    }

    public function render(): View|Closure|string
    {
        return view('components.klarifikasilayouts.sidebarjig');
    }
}
