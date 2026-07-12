<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->string('category')->toString();

        return view('admin.faqs.index', [
            'faqs' => Faq::query()
                ->when($category, fn ($builder) => $builder->where('category', $category))
                ->orderBy('sort_order')
                ->latest()
                ->paginate(12)
                ->withQueryString(),
            'categories' => Faq::whereNotNull('category')->distinct()->orderBy('category')->pluck('category'),
            'category' => $category,
        ]);
    }

    public function create(): View
    {
        return view('admin.faqs.create', ['faq' => new Faq()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $faq = Faq::create($this->validated($request));

        return redirect()->route('admin.faqs.edit', $faq)->with('status', 'FAQ creee.');
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faqs.edit', ['faq' => $faq]);
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $faq->update($this->validated($request));

        return back()->with('status', 'FAQ mise a jour.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'question' => ['required', 'string', 'max:220'],
            'answer' => ['required', 'string', 'max:4000'],
            'category' => ['nullable', 'string', 'max:80'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_published' => ['nullable', 'boolean'],
        ]) + ['is_published' => false];
    }
}
