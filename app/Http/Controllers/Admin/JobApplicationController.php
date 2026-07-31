<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Notifications\PortalStatusNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class JobApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $query = $request->string('q')->toString();

        return view('admin.applications.index', [
            'applications' => JobApplication::query()
                ->with(['jobPosting', 'user.profile', 'reviewer'])
                ->when($status, fn ($builder) => $builder->where('status', $status))
                ->when($query, function ($builder) use ($query): void {
                    $builder->where(function ($nested) use ($query): void {
                        $nested->where('name', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%")
                            ->orWhereHas('jobPosting', fn ($job) => $job->where('title', 'like', "%{$query}%"));
                    });
                })
                ->latest()
                ->paginate(12)
                ->withQueryString(),
            'statuses' => JobApplication::statuses(),
            'status' => $status,
            'query' => $query,
        ]);
    }

    public function show(JobApplication $application): View
    {
        return view('admin.applications.show', [
            'application' => $application->load(['jobPosting', 'user.profile', 'reviewer']),
            'statuses' => JobApplication::statuses(),
        ]);
    }

    public function update(Request $request, JobApplication $application): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(JobApplication::statuses()))],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $application->update([
            'status' => $validated['status'],
            'admin_note' => $validated['admin_note'] ?? null,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        $application->user?->notify(new PortalStatusNotification(
            'Candidature mise a jour',
            ($validated['admin_note'] ?? null) ?: 'Le statut de votre candidature a été mis à jour.',
            'candidature',
            route('portal.dashboard'),
        ));

        return back()->with('application_review_status', 'Candidature mise a jour.');
    }

    public function download(JobApplication $application): StreamedResponse
    {
        abort_unless($application->resume_path && Storage::disk('local')->exists($application->resume_path), 404);

        return Storage::disk('local')->download($application->resume_path, 'cv-'.$application->id);
    }
}
