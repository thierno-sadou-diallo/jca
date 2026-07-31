<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\CooperationProject;
use App\Models\Faq;
use App\Models\HumanitarianProgram;
use App\Models\Partner;
use Illuminate\View\View;

class PublicContentController extends Controller
{
    public function blog(): View
    {
        return $this->articles(Article::TYPE_BLOG, 'Blog', 'Conseils pratiques', 'Des guides pour mieux préparer vos projets de mobilité, emploi et coopération.');
    }

    public function news(): View
    {
        return $this->articles(Article::TYPE_NEWS, 'Actualites', 'Veille et communiques', 'Suivez les annonces, opportunites et informations importantes publiees par JCA.');
    }

    public function article(Article $article): View
    {
        abort_unless($article->status === 'published', 404);

        return view('public.article-show', [
            'article' => $article->load('author'),
        ]);
    }

    public function faq(): View
    {
        return view('public.faq', [
            'faqs' => Faq::where('is_published', true)->orderBy('sort_order')->get()->groupBy(fn (Faq $faq) => $faq->category ?: 'General'),
        ]);
    }

    public function partners(): View
    {
        return view('public.partners', [
            'partners' => Partner::query()->latest('is_featured')->latest()->get(),
            'featuredPartners' => Partner::where('is_featured', true)->latest()->get(),
        ]);
    }

    public function cooperationProjects(): View
    {
        return view('public.cooperation-projects', [
            'projects' => CooperationProject::where('status', 'active')
                ->latest('starts_at')
                ->paginate(9),
        ]);
    }

    public function cooperationProject(CooperationProject $project): View
    {
        abort_unless($project->status === 'active', 404);

        return view('public.cooperation-project-show', [
            'project' => $project,
        ]);
    }

    public function humanitarianPrograms(): View
    {
        return view('public.humanitarian-programs', [
            'programs' => HumanitarianProgram::where('status', 'active')
                ->latest()
                ->paginate(9),
        ]);
    }

    public function humanitarianProgram(HumanitarianProgram $program): View
    {
        abort_unless($program->status === 'active', 404);

        return view('public.humanitarian-program-show', [
            'program' => $program,
        ]);
    }

    private function articles(string $type, string $title, string $eyebrow, string $intro): View
    {
        return view('public.articles', [
            'articles' => Article::where('status', 'published')
                ->where('type', $type)
                ->latest('published_at')
                ->paginate(9),
            'title' => $title,
            'eyebrow' => $eyebrow,
            'intro' => $intro,
            'type' => $type,
        ]);
    }
}
