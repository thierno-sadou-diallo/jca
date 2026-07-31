<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortalReviewController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'quote' => ['required', 'string', 'min:20', 'max:1200'],
        ], [
            'quote.required' => 'Veuillez ecrire votre avis.',
            'quote.min' => 'Votre avis doit contenir au moins :min caracteres.',
        ]);

        DB::table('testimonials')->insert([
            'user_id' => $request->user()->id,
            'author_name' => $request->user()->name,
            'author_role' => 'Client',
            'quote' => $validated['quote'],
            'is_published' => false,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('review_status', 'Merci. Votre avis a été envoyé à JCA.');
    }
}
