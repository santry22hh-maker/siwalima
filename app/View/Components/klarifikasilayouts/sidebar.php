<?php

namespace App\View\Components\klarifikasilayouts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class sidebar extends Component
{
    /**
     * Create a new component instance.
     */
    public $links;

    public function __construct()
    {
        $this->links = [
            [
                'name' => 'Dashboard',
                'route' => 'klarifikasi.dashboard',
                'is_active' => request()->routeIs('klarifikasi.dashboard'),
            ],
            [
                'name' => 'Data IGT',
                'route' => 'klarifikasi.dataigt',
                'is_active' => request()->routeIs('klarifikasi.dataigt'),
            ],
            [
                'name' => 'Layanan Pengaduan',
                'route' => 'klarifikasi.pengaduan',
                'is_active' => request()->routeIs('klarifikasi.pengaduan'),
            ]
        ];  
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.klarifikasilayouts.sidebarklarifikasi');
    }
}
