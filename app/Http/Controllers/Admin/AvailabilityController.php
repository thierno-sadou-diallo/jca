<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AvailabilityController extends Controller
{
    public function index(): View
    {
        return view('admin.availability.index', [
            'slots' => DB::table('appointment_slots')
                ->where('starts_at', '>=', now()->startOfDay())
                ->orderBy('starts_at')
                ->paginate(16),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'duration_minutes' => ['required', 'integer', 'in:30,45,60,90'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $startsAt = Carbon::parse($validated['date'].' '.$validated['time']);
        $endsAt = $startsAt->copy()->addMinutes((int) $validated['duration_minutes']);

        DB::table('appointment_slots')->insert([
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'available',
            'notes' => $validated['notes'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('status', 'Disponibilite ajoutee.');
    }

    public function destroy(int $slot): RedirectResponse
    {
        DB::table('appointment_slots')
            ->where('id', $slot)
            ->where('status', 'available')
            ->delete();

        return back()->with('status', 'Disponibilite supprimee.');
    }
}
