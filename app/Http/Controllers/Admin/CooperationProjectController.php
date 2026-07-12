<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CooperationProject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CooperationProjectController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        return view('admin.cooperation-projects.index', [
            'projects' => CooperationProject::query()
                ->when($status, fn ($builder) => $builder->where('status', $status))
                ->latest()
                ->paginate(12)
                ->withQueryString(),
            'status' => $status,
        ]);
    }

    public function create(): View
    {
        return view('admin.cooperation-projects.create', ['project' => new CooperationProject()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = $this->uniqueSlug($validated['title']);
        $validated['indicators'] = $this->normalizeMetrics($validated['indicators'] ?? []);
        $validated = $this->storeImage($request, $validated);

        $project = CooperationProject::create($validated);

        return redirect()->route('admin.cooperation-projects.edit', $project)->with('status', 'Projet de cooperation cree.');
    }

    public function edit(CooperationProject $project): View
    {
        return view('admin.cooperation-projects.edit', ['project' => $project]);
    }

    public function update(Request $request, CooperationProject $project): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = $project->slug ?: $this->uniqueSlug($validated['title'], $project);
        $validated['indicators'] = $this->normalizeMetrics($validated['indicators'] ?? []);
        $validated = $this->storeImage($request, $validated, $project);

        $project->update($validated);

        return back()->with('status', 'Projet de cooperation mis a jour.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'country' => ['nullable', 'string', 'max:80'],
            'sector' => ['nullable', 'string', 'max:120'],
            'status' => ['required', Rule::in(['draft', 'active', 'completed', 'archived'])],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:3072'],
            'indicators' => ['nullable', 'array'],
            'indicators.*.value' => ['nullable', 'string', 'max:40'],
            'indicators.*.label' => ['nullable', 'string', 'max:120'],
        ]);
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     * @return array<int, array{value: string, label: string}>
     */
    private function normalizeMetrics(array $rows): array
    {
        $metrics = [];

        foreach ($rows as $row) {
            $value = trim((string) ($row['value'] ?? ''));
            $label = trim((string) ($row['label'] ?? ''));

            if ($value === '' && $label === '') {
                continue;
            }

            $metrics[] = [
                'value' => $value,
                'label' => $label,
            ];
        }

        return $metrics;
    }

    /**
     * @param array<string, mixed> $validated
     * @return array<string, mixed>
     */
    private function storeImage(Request $request, array $validated, ?CooperationProject $project = null): array
    {
        if ($request->hasFile('image')) {
            if ($project?->image_path) {
                Storage::disk('public')->delete($project->image_path);
            }

            $validated['image_path'] = Storage::disk('public')->putFile('cooperation-projects', $validated['image']);
        }

        unset($validated['image']);

        return $validated;
    }

    private function uniqueSlug(string $title, ?CooperationProject $project = null): string
    {
        $base = Str::slug($title) ?: 'projet';
        $slug = $base;
        $index = 2;

        while (CooperationProject::where('slug', $slug)->when($project, fn ($query) => $query->whereKeyNot($project->id))->exists()) {
            $slug = "{$base}-{$index}";
            $index++;
        }

        return $slug;
    }
}
