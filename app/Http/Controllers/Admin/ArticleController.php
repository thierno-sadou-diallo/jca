<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $query = $request->string('q')->toString();

        return view('admin.articles.index', [
            'articles' => Article::query()
                ->with('author')
                ->when($status, fn ($builder) => $builder->where('status', $status))
                ->when($query, fn ($builder) => $builder->where('title', 'like', "%{$query}%"))
                ->latest()
                ->paginate(12)
                ->withQueryString(),
            'status' => $status,
            'query' => $query,
        ]);
    }

    public function create(): View
    {
        return view('admin.articles.create', ['article' => new Article()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['user_id'] = $request->user()->id;
        $validated['slug'] = $this->uniqueSlug($validated['title']);
        $validated['published_at'] = $validated['status'] === 'published' ? now() : null;

        $article = Article::create($validated);

        return redirect()->route('admin.articles.edit', $article)->with('status', 'Article créé.');
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.edit', ['article' => $article]);
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = $article->slug ?: $this->uniqueSlug($validated['title'], $article);
        $validated['published_at'] = $validated['status'] === 'published'
            ? ($article->published_at ?: now())
            : null;

        $article->update($validated);

        return back()->with('status', 'Article mis à jour.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'type' => ['required', Rule::in(array_keys(Article::types()))],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);
    }

    private function uniqueSlug(string $title, ?Article $article = null): string
    {
        $base = Str::slug($title) ?: 'article';
        $slug = $base;
        $index = 2;

        while (Article::where('slug', $slug)->when($article, fn ($query) => $query->whereKeyNot($article->id))->exists()) {
            $slug = "{$base}-{$index}";
            $index++;
        }

        return $slug;
    }
}
