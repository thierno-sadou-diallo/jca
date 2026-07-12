<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PartnerController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->string('type')->toString();
        $country = $request->string('country')->toString();

        return view('admin.partners.index', [
            'partners' => Partner::query()
                ->when($type, fn ($builder) => $builder->where('type', $type))
                ->when($country, fn ($builder) => $builder->where('country', $country))
                ->latest()
                ->paginate(12)
                ->withQueryString(),
            'types' => Partner::whereNotNull('type')->distinct()->orderBy('type')->pluck('type'),
            'countries' => Partner::whereNotNull('country')->distinct()->orderBy('country')->pluck('country'),
            'type' => $type,
            'country' => $country,
        ]);
    }

    public function create(): View
    {
        return view('admin.partners.create', ['partner' => new Partner()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $partner = Partner::create($this->validated($request));

        return redirect()->route('admin.partners.edit', $partner)->with('status', 'Partenaire cree.');
    }

    public function edit(Partner $partner): View
    {
        return view('admin.partners.edit', ['partner' => $partner]);
    }

    public function update(Request $request, Partner $partner): RedirectResponse
    {
        $partner->update($this->validated($request, $partner));

        return back()->with('status', 'Partenaire mis a jour.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Partner $partner = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'type' => ['nullable', 'string', 'max:80'],
            'country' => ['nullable', 'string', 'max:80'],
            'website' => ['nullable', 'url', 'max:255'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $validated['is_featured'] = (bool) ($validated['is_featured'] ?? false);

        if ($request->hasFile('logo')) {
            if ($partner?->logo_path) {
                Storage::disk('public')->delete($partner->logo_path);
            }

            $validated['logo_path'] = Storage::disk('public')->putFile('partners', $validated['logo']);
        }

        unset($validated['logo']);

        return $validated;
    }
}
