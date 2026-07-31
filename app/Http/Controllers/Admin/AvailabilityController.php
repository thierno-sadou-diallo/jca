<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\AppointmentCalendar;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AvailabilityController extends Controller
{
    public function index(): View
    {
        $calendarSlots = DB::table('appointment_slots')
            ->where('starts_at', '>=', now()->startOfMonth())
            ->where('starts_at', '<=', now()->addMonth()->endOfMonth())
            ->orderBy('starts_at')
            ->get();

        return view('admin.availability.index', [
            'calendars' => AppointmentCalendar::months($calendarSlots),
            'slots' => DB::table('appointment_slots')
                ->where('starts_at', '>=', now()->startOfDay())
                ->orderBy('starts_at')
                ->paginate(16),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'dates' => ['nullable', 'array'],
            'dates.*' => ['date', 'after_or_equal:today'],
            'date' => ['nullable', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'duration_minutes' => ['required', 'integer', 'in:30,45,60,90'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $dates = collect($validated['dates'] ?? [])
            ->when(empty($validated['dates']) && ! empty($validated['date']), fn ($collection) => $collection->push($validated['date']))
            ->unique()
            ->values();

        if ($dates->isEmpty()) {
            return back()->withErrors(['dates' => 'Sélectionnez au moins un jour disponible.']);
        }

        $created = 0;

        foreach ($dates as $date) {
            $startsAt = Carbon::parse($date.' '.$validated['time']);
            $endsAt = $startsAt->copy()->addMinutes((int) $validated['duration_minutes']);
            $exists = DB::table('appointment_slots')
                ->where('starts_at', $startsAt)
                ->where('status', 'available')
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('appointment_slots')->insert([
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'status' => 'available',
                'notes' => $validated['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $created++;
        }

        return back()->with('status', $created > 1
            ? $created.' disponibilités ajoutées.'
            : 'Disponibilité ajoutée.');
    }

    public function destroy(int $slot): RedirectResponse
    {
        DB::table('appointment_slots')
            ->where('id', $slot)
            ->where('status', 'available')
            ->delete();

        return back()->with('status', 'Disponibilité supprimée.');
    }
}
