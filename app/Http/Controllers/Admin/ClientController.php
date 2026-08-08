<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\ImmigrationCase;
use App\Models\JobApplication;
use App\Models\LeadRequest;
use App\Models\Message;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->string('q')->toString();
        $typeClient = $request->string('type_client')->toString();
        $status = $request->string('status')->toString();

        $clients = User::query()
            ->where('role', 'client')
            ->with('profile')
            ->withCount('documents')
            ->when($query, function ($builder) use ($query): void {
                $builder->where(function ($nested) use ($query): void {
                    $nested->where('name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->orWhere('phone', 'like', "%{$query}%");
                });
            })
            ->when($status, fn ($builder) => $builder->where('status', $status))
            ->when($typeClient, function ($builder) use ($typeClient): void {
                $builder->whereHas('profile', fn ($profile) => $profile->where('type_client', $typeClient));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.clients.index', [
            'clients' => $clients,
            'query' => $query,
            'typeClient' => $typeClient,
            'status' => $status,
            'clientTypes' => UserProfile::clientTypes(),
        ]);
    }

    public function show(User $client): View
    {
        abort_unless($client->role === 'client', 404);

        $client->load('profile');

        return view('admin.clients.show', [
            'client' => $client,
            'clientTypes' => UserProfile::clientTypes(),
            'documents' => Document::where('user_id', $client->id)->latest()->limit(10)->get(),
            'leads' => LeadRequest::where('email', $client->email)->latest()->limit(10)->get(),
            'applications' => JobApplication::where('email', $client->email)->latest()->limit(10)->get(),
            'appointments' => \DB::table('appointments')->where('user_id', $client->id)->latest()->limit(10)->get(),
            'immigrationCases' => ImmigrationCase::where('user_id', $client->id)->latest()->limit(10)->get(),
            'messages' => Message::where('sender_id', $client->id)
                ->orWhere('recipient_id', $client->id)
                ->latest()
                ->limit(6)
                ->get(),
            'stats' => [
                'documents' => Document::where('user_id', $client->id)->count(),
                'leads' => LeadRequest::where('email', $client->email)->count(),
                'applications' => JobApplication::where('email', $client->email)->count(),
                'appointments' => \DB::table('appointments')->where('user_id', $client->id)->count(),
                'immigrationCases' => ImmigrationCase::where('user_id', $client->id)->count(),
                'messages' => Message::where('sender_id', $client->id)->orWhere('recipient_id', $client->id)->count(),
            ],
        ]);
    }

    public function update(Request $request, User $client): RedirectResponse
    {
        abort_unless($client->role === 'client', 404);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['active', 'inactive', 'suspended'])],
            'type_client' => ['required', Rule::in(UserProfile::clientTypes())],
            'organization_name' => ['nullable', 'string', 'max:160'],
            'country' => ['nullable', 'string', 'max:80'],
            'city' => ['nullable', 'string', 'max:120'],
        ]);

        $client->update(['status' => $validated['status']]);

        $client->profile()->updateOrCreate(
            ['user_id' => $client->id],
            [
                'account_type' => 'client',
                'type_client' => $validated['type_client'],
                'organization_name' => $validated['organization_name'] ?? null,
                'country' => $validated['country'] ?? null,
                'city' => $validated['city'] ?? null,
                'preferred_language' => $client->profile?->preferred_language ?? 'fr',
            ],
        );

        return back()->with('status', 'Profil client mis à jour.');
    }
}
