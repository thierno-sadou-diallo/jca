<?php

namespace App\Http\Controllers;

use App\Models\LeadRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortalAppointmentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'slot_id' => ['required', 'integer', 'exists:appointment_slots,id'],
        ]);

        $booked = DB::transaction(function () use ($request, $validated): bool {
            $slot = DB::table('appointment_slots')
                ->where('id', $validated['slot_id'])
                ->where('status', 'available')
                ->where('starts_at', '>=', now())
                ->lockForUpdate()
                ->first();

            if (! $slot) {
                return false;
            }

            $startsAt = now()->parse($slot->starts_at);
            $endsAt = now()->parse($slot->ends_at);

            $lead = LeadRequest::create([
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'phone' => $request->user()->phone,
                'topic' => 'Demande de rendez-vous',
                'message' => 'Le client a demande un rendez-vous depuis son espace personnel.',
                'source' => 'portal',
                'page_slug' => 'espace',
                'preferred_date' => $startsAt->toDateString(),
                'preferred_channel' => 'En ligne',
                'status' => 'new',
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 500),
                'payload' => [
                    'appointment_requested_at' => $startsAt->toIso8601String(),
                    'appointment_slot_id' => $slot->id,
                    'appointment_week_choice' => $request->string('week_choice')->toString(),
                ],
            ]);

            $appointmentId = DB::table('appointments')->insertGetId([
                'user_id' => $request->user()->id,
                'lead_request_id' => $lead->id,
                'topic' => 'Rendez-vous strategique',
                'starts_at' => $slot->starts_at,
                'duration_minutes' => max(15, $endsAt->diffInMinutes($startsAt)),
                'channel' => 'online',
                'status' => 'confirmed',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('appointment_slots')->where('id', $slot->id)->update([
                'status' => 'booked',
                'appointment_id' => $appointmentId,
                'updated_at' => now(),
            ]);

            return true;
        });

        if (! $booked) {
            return back()->withErrors(['slot_id' => 'Ce creneau n est plus disponible.']);
        }

        return back()->with('appointment_status', 'Votre rendez-vous a ete confirme.');
    }
}
