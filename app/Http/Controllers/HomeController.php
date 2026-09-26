<?php

namespace App\Http\Controllers;

use App\Models\Testimony;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the public foundation homepage.
     */
    public function __invoke(): View
    {
        return view('home', [
            'testimonies' => Testimony::query()
                ->approved()
                ->orderByDesc('id')
                ->get(),
        ]);
    }
}
