<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    public function store(Request $request, JobPosting $job): RedirectResponse
    {
        abort_unless($job->status === 'published', 404);

        $validated = $request->validate([
            'country' => ['nullable', 'string', 'max:80'],
            'phone' => ['nullable', 'string', 'max:40'],
            'resume' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:8192'],
            'message' => ['nullable', 'string', 'max:2000'],
        ], [
            'resume.required' => 'Veuillez joindre votre CV.',
            'resume.mimes' => 'Formats acceptes pour le CV: PDF, DOC ou DOCX.',
            'resume.max' => 'Le CV ne doit pas depasser 8 Mo.',
        ]);

        $user = $request->user();
        $path = Storage::disk('local')->putFile('job-applications/'.$user->id, $validated['resume']);

        JobApplication::create([
            'job_posting_id' => $job->id,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $validated['phone'] ?? $user->phone,
            'country' => $validated['country'] ?? $user->profile?->country,
            'resume_path' => $path,
            'message' => $validated['message'] ?? null,
            'status' => JobApplication::STATUS_NEW,
        ]);

        return back()->with('application_status', 'Votre candidature a ete transmise a JCA.');
    }
}
