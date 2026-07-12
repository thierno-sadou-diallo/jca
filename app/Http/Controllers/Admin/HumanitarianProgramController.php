<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HumanitarianProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HumanitarianProgramController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        return view('admin.humanitarian-programs.index', [
            'programs' => HumanitarianProgram::query()
                ->when($status, fn ($builder) => $builder->where('status', $status))
                ->latest()
                ->paginate(12)
                ->withQueryString(),
            'status' => $status,
        ]);
    }

    public function create(): View
    {
        return view('admin.humanitarian-programs.create', ['program' => new HumanitarianProgram()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = $this->uniqueSlug($validated['title']);
        $validated['impact_metrics'] = $this->normalizeMetrics($validated['impact_metrics'] ?? []);
        $validated = $this->storeImage($request, $validated);

        $program = HumanitarianProgram::create($validated);

        return redirect()->route('admin.humanitarian-programs.edit', $program)->with('status', 'Programme humanitaire cree.');
    }

    public function edit(HumanitarianProgram $program): View
    {
        return view('admin.humanitarian-programs.edit', ['program' => $program]);
    }

    public function update(Request $request, HumanitarianProgram $program): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = $program->slug ?: $this->uniqueSlug($validated['title'], $program);
        $validated['impact_metrics'] = $this->normalizeMetrics($validated['impact_metrics'] ?? []);
        $validated = $this->storeImage($request, $validated, $program);

        $program->update($validated);

        return back()->with('status', 'Programme humanitaire mis a jour.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'country' => ['nullable', 'string', 'max:80'],
            'focus_area' => ['nullable', 'string', 'max:120'],
            'status' => ['required', Rule::in(['draft', 'active', 'completed', 'archived'])],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:3072'],
            'impact_metrics' => ['nullable', 'array'],
            'impact_metrics.*.value' => ['nullable', 'string', 'max:40'],
            'impact_metrics.*.label' => ['nullable', 'string', 'max:120'],
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
    private function storeImage(Request $request, array $validated, ?HumanitarianProgram $program = null): array
    {
        if ($request->hasFile('image')) {
            if ($program?->image_path) {
                Storage::disk('public')->delete($program->image_path);
            }

            $validated['image_path'] = Storage::disk('public')->putFile('humanitarian-programs', $validated['image']);
        }

        unset($validated['image']);

        return $validated;
    }

    private function uniqueSlug(string $title, ?HumanitarianProgram $program = null): string
    {
        $base = Str::slug($title) ?: 'programme';
        $slug = $base;
        $index = 2;

        while (HumanitarianProgram::where('slug', $slug)->when($program, fn ($query) => $query->whereKeyNot($program->id))->exists()) {
            $slug = "{$base}-{$index}";
            $index++;
        }

        return $slug;
    }
}
