<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PublicTestimonialController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        abort_if(filled($request->input('website')), 204);

        $validated = $request->validate([
            'author_name' => ['required', 'string', 'max:120'],
            'author_role' => ['nullable', 'string', 'max:120'],
            'organization' => ['nullable', 'string', 'max:160'],
            'quote' => ['required', 'string', 'min:20', 'max:1200'],
        ], [
            'author_name.required' => 'Veuillez indiquer votre nom.',
            'quote.required' => 'Veuillez écrire votre témoignage.',
            'quote.min' => 'Votre témoignage doit contenir au moins :min caractères.',
        ]);

        $payload = [
            'user_id' => null,
            'author_name' => $validated['author_name'],
            'author_role' => $validated['author_role'] ?? 'Visiteur',
            'organization' => $validated['organization'] ?? null,
            'quote' => $validated['quote'],
            'is_published' => true,
            'status' => 'published',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('testimonials', 'locale')) {
            $payload['locale'] = app()->getLocale();
        }

        DB::table('testimonials')->insert($payload);

        return back()->with('testimonial_status', 'Merci. Votre témoignage est maintenant publié.');
    }
}
