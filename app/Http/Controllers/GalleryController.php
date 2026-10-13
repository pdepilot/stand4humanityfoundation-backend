<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use Illuminate\View\View;

class GalleryController extends Controller
{
    /**
     * Show the public gallery.
     */
    public function index(): View
    {
        return view('site.gallery.index', [
            'items' => GalleryItem::query()->latest('id')->get(),
        ]);
    }

    /**
     * Show one picture or video with its title and details.
     */
    public function show(GalleryItem $galleryItem): View
    {
        return view('site.gallery.show', [
            'item' => $galleryItem,
        ]);
    }
}
