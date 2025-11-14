<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class JigLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    // public function render(): View
    // {
    //     return view('layouts.app');
    // }
    public function render(): View
    {
        return view('jig.app');
    }
}
