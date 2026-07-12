<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Notifications\PortalStatusNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $query = $request->string('q')->toString();

        return view('admin.appointments.index', [
            'appointments' => Appointment::query()
                ->with(['user', 'leadRequest'])
                ->when($status, fn ($builder) => $builder->where('status', $status))
                ->when($query, function ($builder) use ($query): void {
                    $builder->where(function ($nested) use ($query): void {
                        $nested->where('topic', 'like', "%{$query}%")
                            ->orWhere('channel', 'like', "%{$query}%")
                            ->orWhereHas('user', fn ($user) => $user->where('name', 'like', "%{$query}%")->orWhere('email', 'like', "%{$query}%"));
                    });
                })
                ->latest('starts_at')
                ->paginate(12)
                ->withQueryString(),
            'statuses' => Appointment::statuses(),
            'status' => $status,
            'query' => $query,
        ]);
    }

    public function show(Appointment $appointment): View
    {
        return view('admin.appointments.show', [
            'appointment' => $appointment->load(['user.profile', 'leadRequest']),
            'statuses' => Appointment::statuses(),
        ]);
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'topic' => ['required', 'string', 'max:120'],
            'starts_at' => ['nullable', 'date'],
            'duration_minutes' => ['required', 'integer', 'in:30,45,60,90'],
            'channel' => ['required', 'string', 'max:40'],
            'status' => ['required', Rule::in(array_keys(Appointment::statuses()))],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $appointment->update($validated);

        if ($appointment->lead_request_id) {
            $payload = $appointment->leadRequest?->payload ?? [];
            $payload['appointment_admin_status'] = $validated['status'];
            $payload['appointment_admin_note'] = $validated['notes'] ?? null;
            $payload['appointment_updated_at'] = now()->toIso8601String();

            $appointment->leadRequest?->update([
                'preferred_date' => $appointment->starts_at?->toDateString(),
                'payload' => $payload,
            ]);
        }

        $appointment->user?->notify(new PortalStatusNotification(
            'Rendez-vous mis a jour',
            ($validated['notes'] ?? null) ?: 'Le statut de votre rendez-vous a ete mis a jour.',
            'rendez-vous',
            route('portal.dashboard'),
        ));

        return back()->with('status', 'Rendez-vous mis a jour.');
    }
}
