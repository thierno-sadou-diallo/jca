<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        return view('admin.reviews.index', [
            'reviews' => DB::table('testimonials')->latest()->paginate(12),
        ]);
    }

    public function update(Request $request, int $review): RedirectResponse
    {
        $validated = $request->validate([
            'admin_response' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:pending,published,closed'],
        ]);

        DB::table('testimonials')
            ->where('id', $review)
            ->update([
                'admin_response' => $validated['admin_response'] ?? null,
                'responded_at' => filled($validated['admin_response'] ?? null) ? now() : null,
                'status' => $validated['status'],
                'is_published' => $validated['status'] === 'published',
                'updated_at' => now(),
            ]);

        return back()->with('status', 'Avis mis à jour.');
    }

    public function destroy(int $review): RedirectResponse
    {
        DB::table('testimonials')->where('id', $review)->delete();

        return back()->with('status', 'Avis supprime.');
    }
}
