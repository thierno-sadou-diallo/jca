<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\LeadRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LeadRequestController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        return view('admin.leads.index', [
            'status' => $status,
            'leads' => LeadRequest::query()
                ->withCount('documents')
                ->when($status, fn ($query) => $query->where('status', $status))
                ->latest()
                ->paginate(12)
                ->withQueryString(),
        ]);
    }

    public function show(LeadRequest $lead): View
    {
        $client = User::where('email', $lead->email)->first();
        $lead->load('documents');
        $leadDocumentIds = $lead->documents->pluck('id');

        return view('admin.leads.show', [
            'lead' => $lead,
            'clientDocuments' => $client
                ? Document::where('user_id', $client->id)->whereNotIn('id', $leadDocumentIds)->latest()->get()
                : collect(),
        ]);
    }

    public function update(Request $request, LeadRequest $lead): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['new', 'in_review', 'contacted', 'converted', 'closed'])],
            'admin_note' => ['nullable', 'string', 'max:2000'],
            'response_message' => ['nullable', 'string', 'max:3000'],
        ]);

        $payload = $lead->payload ?? [];
        $payload['admin_note'] = $validated['admin_note'] ?? null;
        $payload['response_message'] = $validated['response_message'] ?? null;
        $payload['responded_at'] = filled($validated['response_message'] ?? null) ? now()->toIso8601String() : ($payload['responded_at'] ?? null);
        $payload['processed_by'] = $request->user()->id;
        $payload['processed_at'] = now()->toIso8601String();

        $lead->update([
            'status' => $validated['status'],
            'payload' => $payload,
        ]);

        DB::table('activity_logs')->insert([
            'user_id' => $request->user()->id,
            'action' => 'lead_updated',
            'subject_type' => LeadRequest::class,
            'subject_id' => $lead->id,
            'properties' => json_encode(['status' => $validated['status']]),
            'ip_address' => $request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('status', 'Demande mise a jour.');
    }
}
