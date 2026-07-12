<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => SiteSetting::publicValues(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'brand_name' => ['required', 'string', 'max:80'],
            'brand_tagline' => ['nullable', 'string', 'max:160'],
            'footer_description' => ['nullable', 'string', 'max:1000'],
            'contact_email' => ['required', 'email', 'max:160'],
            'contact_phone' => ['nullable', 'string', 'max:60'],
            'whatsapp' => ['nullable', 'string', 'max:80'],
            'address' => ['nullable', 'string', 'max:255'],
            'footer_signature' => ['nullable', 'string', 'max:220'],
        ]);

        foreach ($validated as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'group' => str_starts_with($key, 'contact') || $key === 'whatsapp' || $key === 'address'
                        ? 'contact'
                        : 'general',
                ],
            );
        }

        return back()->with('status', 'Parametres du site mis a jour.');
    }
}
