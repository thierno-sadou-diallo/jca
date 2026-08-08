<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Notifications\PortalStatusNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $query = $request->string('q')->toString();

        return view('admin.documents.index', [
            'documents' => Document::query()
                ->with(['user', 'leadRequest', 'reviewer'])
                ->when($status, fn ($builder) => $builder->where('status', $status))
                ->when($query, function ($builder) use ($query): void {
                    $builder->where(function ($nested) use ($query): void {
                        $nested->where('title', 'like', "%{$query}%")
                            ->orWhere('type', 'like', "%{$query}%")
                            ->orWhereHas('user', fn ($user) => $user->where('name', 'like', "%{$query}%")->orWhere('email', 'like', "%{$query}%"));
                    });
                })
                ->latest()
                ->paginate(12)
                ->withQueryString(),
            'statuses' => Document::statuses(),
            'status' => $status,
            'query' => $query,
        ]);
    }

    public function update(Request $request, Document $document): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(Document::statuses()))],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $document->update([
            'status' => $validated['status'],
            'admin_note' => $validated['admin_note'] ?? null,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        $document->user?->notify(new PortalStatusNotification(
            'Document mis à jour',
            ($validated['admin_note'] ?? null) ?: 'Le statut de votre document '.$document->title.' a été mis à jour.',
            'document',
            route('portal.dashboard'),
        ));

        return back()->with('document_review_status', 'Statut du document mis à jour.');
    }

    public function download(Document $document): StreamedResponse
    {
        abort_unless(Storage::disk('local')->exists($document->path), 404);

        return Storage::disk('local')->download($document->path, $document->title);
    }
}
