<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImmigrationCase;
use App\Models\LeadRequest;
use App\Models\User;
use App\Notifications\PortalStatusNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ImmigrationCaseController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $query = $request->string('q')->toString();

        return view('admin.immigration-cases.index', [
            'cases' => ImmigrationCase::query()
                ->with(['user', 'leadRequest'])
                ->when($status, fn ($builder) => $builder->where('status', $status))
                ->when($query, function ($builder) use ($query): void {
                    $builder->where(function ($nested) use ($query): void {
                        $nested->where('reference', 'like', "%{$query}%")
                            ->orWhere('program_type', 'like', "%{$query}%")
                            ->orWhere('destination_country', 'like', "%{$query}%")
                            ->orWhereHas('user', fn ($user) => $user->where('name', 'like', "%{$query}%")->orWhere('email', 'like', "%{$query}%"));
                    });
                })
                ->latest()
                ->paginate(12)
                ->withQueryString(),
            'statuses' => ImmigrationCase::statuses(),
            'status' => $status,
            'query' => $query,
        ]);
    }

    public function create(Request $request): View
    {
        $lead = $request->filled('lead_id')
            ? LeadRequest::find($request->integer('lead_id'))
            : null;
        $client = $request->filled('client_id')
            ? User::where('role', 'client')->find($request->integer('client_id'))
            : ($lead ? User::where('email', $lead->email)->first() : null);

        return view('admin.immigration-cases.create', [
            'lead' => $lead,
            'client' => $client,
            'clients' => User::where('role', 'client')->where('status', 'active')->orderBy('name')->get(),
            'statuses' => ImmigrationCase::statuses(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'lead_request_id' => ['nullable', 'exists:lead_requests,id'],
            'program_type' => ['required', 'string', 'max:120'],
            'destination_country' => ['nullable', 'string', 'max:80'],
            'status' => ['required', Rule::in(array_keys(ImmigrationCase::statuses()))],
            'submitted_at' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        $case = ImmigrationCase::create([
            'user_id' => $validated['user_id'],
            'lead_request_id' => $validated['lead_request_id'] ?? null,
            'reference' => ImmigrationCase::makeReference(),
            'program_type' => $validated['program_type'],
            'destination_country' => $validated['destination_country'] ?? null,
            'status' => $validated['status'],
            'submitted_at' => $validated['submitted_at'] ?? now()->toDateString(),
        ]);

        $case->histories()->create([
            'user_id' => $request->user()->id,
            'status' => $validated['status'],
            'note' => $validated['note'] ?? 'Dossier créé par l’administration.',
        ]);

        if (! empty($validated['lead_request_id'])) {
            LeadRequest::where('id', $validated['lead_request_id'])->update(['status' => 'converted']);
        }

        return redirect()->route('admin.immigration-cases.show', $case)->with('status', 'Dossier immigration créé.');
    }

    public function show(ImmigrationCase $immigrationCase): View
    {
        return view('admin.immigration-cases.show', [
            'case' => $immigrationCase->load(['user.profile', 'leadRequest', 'histories.user']),
            'statuses' => ImmigrationCase::statuses(),
        ]);
    }

    public function update(Request $request, ImmigrationCase $immigrationCase): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(ImmigrationCase::statuses()))],
            'program_type' => ['required', 'string', 'max:120'],
            'destination_country' => ['nullable', 'string', 'max:80'],
            'submitted_at' => ['nullable', 'date'],
            'decision_at' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        $statusChanged = $immigrationCase->status !== $validated['status'];

        $immigrationCase->update([
            'status' => $validated['status'],
            'program_type' => $validated['program_type'],
            'destination_country' => $validated['destination_country'] ?? null,
            'submitted_at' => $validated['submitted_at'] ?? null,
            'decision_at' => $validated['decision_at'] ?? null,
        ]);

        if ($statusChanged || filled($validated['note'] ?? null)) {
            $immigrationCase->histories()->create([
                'user_id' => $request->user()->id,
                'status' => $validated['status'],
                'note' => $validated['note'] ?? 'Statut mis à jour.',
            ]);

            $immigrationCase->user?->notify(new PortalStatusNotification(
                'Dossier immigration mis à jour',
                ($validated['note'] ?? null) ?: 'Le statut de votre dossier '.$immigrationCase->reference.' a été mis à jour.',
                'dossier',
                route('portal.dashboard'),
            ));
        }

        return back()->with('status', 'Dossier immigration mis à jour.');
    }
}
