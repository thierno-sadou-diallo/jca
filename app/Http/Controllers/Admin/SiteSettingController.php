<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'collaboration_document' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'remove_collaboration_document' => ['nullable', 'boolean'],
        ]);

        $document = $validated['collaboration_document'] ?? null;
        $removeDocument = (bool) ($validated['remove_collaboration_document'] ?? false);
        unset($validated['collaboration_document'], $validated['remove_collaboration_document']);

        if ($removeDocument) {
            $existingPath = SiteSetting::publicValues()['collaboration_document_path'] ?? '';

            if ($existingPath !== '') {
                Storage::disk('public')->delete($existingPath);
            }

            $validated['collaboration_document_path'] = '';
            $validated['collaboration_document_name'] = '';
        }

        if ($document !== null) {
            $existingPath = SiteSetting::publicValues()['collaboration_document_path'] ?? '';

            if ($existingPath !== '') {
                Storage::disk('public')->delete($existingPath);
            }

            $validated['collaboration_document_path'] = $document->store('collaboration', 'public');
            $validated['collaboration_document_name'] = $document->getClientOriginalName();
        }

        foreach ($validated as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'group' => str_starts_with($key, 'collaboration_document')
                        ? 'collaboration'
                        : (str_starts_with($key, 'contact') || $key === 'whatsapp' || $key === 'address'
                        ? 'contact'
                        : 'general'),
                ],
            );
        }

        return back()->with('status', 'Parametres du site mis a jour.');
    }
}
