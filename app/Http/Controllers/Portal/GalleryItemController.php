<?php

namespace App\Http\Controllers\Portal;

use App\Actions\StoreGalleryMediaAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\StoreGalleryItemRequest;
use App\Http\Requests\Portal\UpdateGalleryItemRequest;
use App\Models\GalleryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class GalleryItemController extends Controller
{
    /**
     * Display the gallery desk.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', GalleryItem::class);

        return view('portal.gallery.index', [
            'user' => $request->user(),
            'items' => GalleryItem::query()->latest('id')->get(),
        ]);
    }

    /**
     * Show the form for adding a gallery item.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', GalleryItem::class);

        return view('portal.gallery.create', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Add a picture or video to the public gallery.
     */
    public function store(StoreGalleryItemRequest $request, StoreGalleryMediaAction $storeMedia): RedirectResponse
    {
        $details = $request->itemDetails();
        $details['path'] = $storeMedia->store($request->uploadedMedia());

        $item = GalleryItem::query()->create($details);

        return redirect()
            ->route('portal.gallery.show', $item)
            ->with('status', $item->title.' was added to the gallery.');
    }

    /**
     * Display a gallery item.
     */
    public function show(Request $request, GalleryItem $galleryItem): View
    {
        Gate::authorize('view', $galleryItem);

        return view('portal.gallery.show', [
            'user' => $request->user(),
            'item' => $galleryItem,
        ]);
    }

    /**
     * Show the form for changing a gallery item.
     */
    public function edit(Request $request, GalleryItem $galleryItem): View
    {
        Gate::authorize('update', $galleryItem);

        return view('portal.gallery.edit', [
            'user' => $request->user(),
            'item' => $galleryItem,
        ]);
    }

    /**
     * Save the title, details, and an optional replacement file.
     */
    public function update(
        UpdateGalleryItemRequest $request,
        GalleryItem $galleryItem,
        StoreGalleryMediaAction $storeMedia,
    ): RedirectResponse {
        $details = $request->itemDetails();
        $upload = $request->uploadedMedia();

        if ($upload !== null) {
            $previous = $galleryItem->path;
            $details['path'] = $storeMedia->store($upload);
            $storeMedia->delete($previous);
        }

        $galleryItem->update($details);

        return redirect()
            ->route('portal.gallery.show', $galleryItem)
            ->with('status', 'The gallery item was updated.');
    }

    /**
     * Remove a gallery item and its file.
     */
    public function destroy(GalleryItem $galleryItem, StoreGalleryMediaAction $storeMedia): RedirectResponse
    {
        Gate::authorize('delete', $galleryItem);

        $title = $galleryItem->title;
        $storeMedia->delete($galleryItem->path);
        $galleryItem->delete();

        return redirect()
            ->route('portal.gallery.index')
            ->with('status', $title.' was removed from the gallery.');
    }
}
