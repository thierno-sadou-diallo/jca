<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JobPostingController extends Controller
{
    public function index(): View
    {
        return view('admin.jobs.index', [
            'jobs' => JobPosting::latest()->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.jobs.create', ['job' => new JobPosting()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $job = JobPosting::create($this->validatedData($request));
        $this->log($request, 'job_created', $job);

        return redirect()->route('admin.jobs.edit', $job)->with('status', 'Offre créée.');
    }

    public function edit(JobPosting $job): View
    {
        return view('admin.jobs.edit', ['job' => $job]);
    }

    public function update(Request $request, JobPosting $job): RedirectResponse
    {
        $job->update($this->validatedData($request, $job));
        $this->log($request, 'job_updated', $job);

        return back()->with('status', 'Offre mise à jour.');
    }

    private function validatedData(Request $request, ?JobPosting $job = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'company_name' => ['nullable', 'string', 'max:160'],
            'country' => ['required', 'string', 'max:80'],
            'city' => ['nullable', 'string', 'max:120'],
            'sector' => ['required', 'string', 'max:120'],
            'contract_type' => ['nullable', 'string', 'max:80'],
            'description' => ['required', 'string', 'min:40'],
            'requirements' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'published', 'closed'])],
            'expires_at' => ['nullable', 'date', 'after_or_equal:today'],
        ], [
            'title.required' => 'Le titre est obligatoire.',
            'country.required' => 'Le pays est obligatoire.',
            'sector.required' => 'Le secteur est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'description.min' => 'La description doit contenir au moins :min caracteres.',
        ]);

        $validated['slug'] = $job?->slug ?: Str::slug($validated['title']).'-'.Str::lower(Str::random(6));
        $validated['published_at'] = $validated['status'] === 'published'
            ? ($job?->published_at ?: now())
            : null;

        return $validated;
    }

    private function log(Request $request, string $action, JobPosting $job): void
    {
        DB::table('activity_logs')->insert([
            'user_id' => $request->user()->id,
            'action' => $action,
            'subject_type' => JobPosting::class,
            'subject_id' => $job->id,
            'properties' => json_encode(['status' => $job->status]),
            'ip_address' => $request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
