<?php

namespace App\View\Components\klarifikasilayouts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public $links;

    public function __construct()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $links = [];

        if (! $user) {
            $this->links = $links;

            return;
        }

        // ===================================
        // 1. MENU HOME (Selalu Tampil)
        // ===================================
        $links[] = [
            'name' => 'Home',
            'route' => 'dashboard',
            'icon' => 'fas fa-home fa-lg',
            'is_active' => request()->routeIs('dashboard'),
            'is_dropdown' => false,
            'submenu' => [],
        ];

        // ==================================================================
        // LOGIKA PERCABANGAN UTAMA (ADMIN vs PENGGUNA)
        // ==================================================================

        // Cek apakah user adalah Admin/Penelaah Klarifikasi
        if ($user->can('access klarifikasi backend')) {

            // --- JIKA USER ADALAH ADMIN / PENELAAH, TAMPILKAN MENU BACKEND ---
            // Menu-menu ini adalah LINK LANGSUNG, bukan dropdown

            $links[] = [
                'name' => 'Manajemen Permohonan',
                'icon' => 'fas fa-file-signature fa-lg',
                'is_active' => request()->routeIs('adminklarifikasi.permohonan.index', 'adminklarifikasi.permohonan.show'),
                'is_dropdown' => false,
                'submenu' => [],
                'route' => 'adminklarifikasi.permohonan.index',
            ];

            $links[] = [
                'name' => 'Manajemen Pengaduan',
                'icon' => 'fas fa-inbox fa-lg',
                'is_active' => request()->routeIs('adminklarifikasi.pengaduan.index', 'adminklarifikasi.pengaduan.show'),
                'is_dropdown' => false,
                'submenu' => [],
                'route' => 'adminklarifikasi.pengaduan.index',
            ];

            $links[] = [
                'name' => 'Rekapitulasi Survey',
                'icon' => 'fas fa-chart-bar fa-lg', // Icon baru
                'is_active' => request()->routeIs('adminklarifikasi.survey.rekap'),
                'is_dropdown' => false,
                'submenu' => [],
                'route' => 'adminklarifikasi.survey.rekap', // Rute admin yang kita buat
            ];

            // --- TAMBAHAN BARU: Menu Statistik ---
            $statistikSubmenu = [];
            $statistikActiveRoutes = [
                'admin.klarifikasi.statistik.permohonan',
                'admin.klarifikasi.statistik.pengaduan',
                'admin.klarifikasi.statistik.survey',
            ];

            $statistikSubmenu[] = ['name' => 'Statistik Permohonan', 'route' => 'adminklarifikasi.statistik.permohonan', 'is_active' => request()->routeIs('adminklarifikasi.statistik.permohonan')];
            $statistikSubmenu[] = ['name' => 'Statistik Pengaduan', 'route' => 'adminklarifikasi.statistik.pengaduan', 'is_active' => request()->routeIs('adminklarifikasi.statistik.pengaduan')];
            $statistikSubmenu[] = ['name' => 'Grafik Nilai Kepuasan', 'route' => 'adminklarifikasi.statistik.survey', 'is_active' => request()->routeIs('adminklarifikasi.statistik.survey')];

            $links[] = [
                'name' => 'Statistik',
                'icon' => 'fas fa-chart-pie fa-lg',
                'is_active' => request()->routeIs($statistikActiveRoutes),
                'is_dropdown' => true,
                'submenu' => $statistikSubmenu,
            ];
            // --- Submenu 3: Admin Panel (Hanya untuk Admin) ---
            if ($user->hasRole(['Admin'])) { // Hanya Admin Klarifikasi
                $adminPanelSubmenu = [];
                $adminPanelActiveRoutes = [];

                $adminPanelSubmenu[] = [
                    'name' => 'Manajemen Penelaah',
                    'route' => 'adminklarifikasi.penelaah.index',
                    'is_active' => request()->routeIs('adminklarifikasi.penelaah.index'),
                ];
                $adminPanelActiveRoutes[] = 'adminklarifikasi.penelaah.index';

                $links[] = [
                    'name' => 'Admin Panel',
                    'icon' => 'fas fa-shield-halved fa-lg',
                    'is_active' => request()->routeIs($adminPanelActiveRoutes),
                    'is_dropdown' => true,
                    'submenu' => $adminPanelSubmenu,
                ];
            }
        } else {
            // --- JIKA USER ADALAH PENGGUNA BIASA, TAMPILKAN MENU FRONTEND ---

            // ===================================
            // 2. MENU ANALISIS MANDIRI
            // ===================================
            $links[] = [
                'name' => 'Analisis Mandiri',
                'route' => 'klarifikasi.input',
                'icon' => 'fas fa-map-marked-alt fa-lg',
                'is_active' => request()->routeIs('klarifikasi.input'),
                'is_dropdown' => false,
                'submenu' => [],
            ];

            // ===================================
            // 3. MENU PERMOHONAN RESMI
            // ===================================
            $permohonanSubmenu = [];
            $permohonanActiveRoutes = ['permohonananalisis.create', 'permohonananalisis.index', 'permohonananalisis.show', 'permohonananalisis.edit'];
            $permohonanSubmenu[] = ['name' => 'Ajukan Permohonan Baru', 'route' => 'permohonananalisis.create', 'is_active' => request()->routeIs('permohonananalisis.create')];
            $permohonanSubmenu[] = ['name' => 'Daftar Permohonan Saya', 'route' => 'permohonananalisis.index', 'is_active' => request()->routeIs('permohonananalisis.index', 'permohonananalisis.show', 'permohonananalisis.edit')];
            $links[] = [
                'name' => 'Permohonan Analisis Resmi',
                'icon' => 'fas fa-file-signature fa-lg',
                'is_active' => request()->routeIs($permohonanActiveRoutes),
                'is_dropdown' => true,
                'submenu' => $permohonanSubmenu,
            ];

            // ===================================
            // 4. MENU PENGADUAN
            // ===================================
            $pengaduanSubmenu = [];
            $pengaduanActiveRoutes = ['pengaduan.klarifikasi.create', 'pengaduan.klarifikasi.index'];
            $pengaduanSubmenu[] = ['name' => 'Ajukan (Klarifikasi)', 'route' => 'pengaduan.klarifikasi.create', 'is_active' => request()->routeIs('pengaduan.klarifikasi.create')];
            $pengaduanSubmenu[] = ['name' => 'Riwayat (Klarifikasi)', 'route' => 'pengaduan.klarifikasi.index', 'is_active' => request()->routeIs('pengaduan.klarifikasi.index')];
            $links[] = [
                'name' => 'Pengaduan',
                'icon' => 'fas fa-inbox fa-lg',
                'is_active' => request()->routeIs($pengaduanActiveRoutes),
                'is_dropdown' => true,
                'submenu' => $pengaduanSubmenu,
            ];

            // ===================================
            // 6. MENU PUSAT INFORMASI
            // ===================================
            $infoSubmenu = [];
            $infoActiveRoutes = ['info.panduan', 'info.faq', 'info.sop', 'info.kontak'];
            $infoSubmenu[] = ['name' => 'Panduan Layanan', 'route' => 'info.panduan', 'is_active' => request()->routeIs('info.panduan')];
            $infoSubmenu[] = ['name' => 'Dasar Hukum & SOP', 'route' => 'info.sop', 'is_active' => request()->routeIs('info.sop')];
            $infoSubmenu[] = ['name' => 'Kontak Layanan', 'route' => 'info.kontak', 'is_active' => request()->routeIs('info.kontak')];
            $links[] = [
                'name' => 'Pusat Informasi',
                'icon' => 'fas fa-book-open fa-lg',
                'is_active' => request()->routeIs($infoActiveRoutes),
                'is_dropdown' => true,
                'submenu' => $infoSubmenu,
            ];
        }

        $this->links = $links;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.klarifikasilayouts.sidebar');
    }
}
