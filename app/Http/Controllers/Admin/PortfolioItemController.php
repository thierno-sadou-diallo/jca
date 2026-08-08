<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PortfolioItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortfolioItemController extends Controller
{
    public function index()
    {
        $items = PortfolioItem::query()
            ->orderBy('sort_order')
            ->latest('event_date')
            ->latest()
            ->paginate(20);

        return view('admin.portfolio.index', compact('items'));
    }

    public function create()
    {
        return view('admin.portfolio.create', ['item' => new PortfolioItem()]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->storeWebpImage($request->file('image'));
        }

        PortfolioItem::create($data);

        return redirect()->route('admin.portfolio.index')->with('status', 'Publication portfolio ajoutee.');
    }

    public function edit(PortfolioItem $portfolio)
    {
        return view('admin.portfolio.edit', ['item' => $portfolio]);
    }

    public function update(Request $request, PortfolioItem $portfolio)
    {
        $data = $this->validatedData($request);
        $data['slug'] = $portfolio->slug ?: $this->uniqueSlug($data['title'], $portfolio->id);
        $data['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('image')) {
            if ($portfolio->image_path) {
                Storage::disk('public')->delete($portfolio->image_path);
            }

            $data['image_path'] = $this->storeWebpImage($request->file('image'));
        }

        $portfolio->update($data);

        return redirect()->route('admin.portfolio.index')->with('status', 'Publication portfolio mise à jour.');
    }

    public function destroy(PortfolioItem $portfolio)
    {
        if ($portfolio->image_path) {
            Storage::disk('public')->delete($portfolio->image_path);
        }

        $portfolio->delete();

        return redirect()->route('admin.portfolio.index')->with('status', 'Publication portfolio supprimee.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'type' => ['required', 'string', 'max:60'],
            'location' => ['nullable', 'string', 'max:160'],
            'event_date' => ['nullable', 'date'],
            'excerpt' => ['nullable', 'string', 'max:600'],
            'body' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: Str::random(8);
        $slug = $base;
        $counter = 2;

        while (PortfolioItem::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }

    private function storeWebpImage($file): string
    {
        $image = match ($file->getMimeType()) {
            'image/jpeg' => imagecreatefromjpeg($file->getRealPath()),
            'image/png' => imagecreatefrompng($file->getRealPath()),
            'image/webp' => imagecreatefromwebp($file->getRealPath()),
            default => null,
        };

        if (! $image) {
            return $file->store('portfolio', 'public');
        }

        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        $path = 'portfolio/'.Str::uuid().'.webp';
        $absolutePath = Storage::disk('public')->path($path);

        if (! is_dir(dirname($absolutePath))) {
            mkdir(dirname($absolutePath), 0775, true);
        }

        imagewebp($image, $absolutePath, 86);
        imagedestroy($image);

        return $path;
    }
}
