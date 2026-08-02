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
        $publishedFaqs = Faq::where('is_published', true)->orderBy('sort_order')->get();
        $defaultFaqs = collect([
                (object) [
                    'category' => 'Immigration & mobilité',
                    'question' => 'Comment savoir si mon profil est admissible à un projet de mobilité?',
                    'answer' => 'JCA commence par analyser votre objectif, votre parcours, votre pays cible, vos délais et vos documents disponibles. Cette lecture permet d’identifier les options réalistes et les étapes à préparer.',
                ],
                (object) [
                    'category' => 'Immigration & mobilité',
                    'question' => 'JCA garantit-il l’obtention d’un visa ou d’un permis?',
                    'answer' => 'Non. Aucune décision administrative ne peut être garantie. JCA sécurise la stratégie, la cohérence documentaire et la préparation du dossier afin de réduire les erreurs et les zones de risque.',
                ],
                (object) [
                    'category' => 'Rendez-vous',
                    'question' => 'Puis-je prendre rendez-vous sans créer de compte?',
                    'answer' => 'Oui. Le formulaire de rendez-vous est accessible directement depuis le site public. Le compte client devient utile ensuite lorsqu’un dossier doit être suivi avec documents, messages et étapes.',
                ],
                (object) [
                    'category' => 'Employeurs',
                    'question' => 'Comment JCA accompagne les employeurs?',
                    'answer' => 'JCA aide les employeurs à clarifier les besoins, identifier des profils, organiser la préqualification et préparer la mobilité professionnelle des talents étrangers.',
                ],
                (object) [
                    'category' => 'Partenariats',
                    'question' => 'Quels partenaires peuvent collaborer avec JCA?',
                    'answer' => 'Les institutions, ONG, gouvernements, organismes de formation, employeurs et partenaires techniques peuvent proposer une collaboration structurée autour d’objectifs mesurables.',
                ],
                (object) [
                    'category' => 'Confidentialité',
                    'question' => 'Mes informations et documents sont-ils protégés?',
                    'answer' => 'Les informations transmises sont traitées avec discrétion et utilisées uniquement pour comprendre la demande, préparer une réponse ou suivre un dossier ouvert avec JCA.',
                ],
            ]);
        $faqs = $defaultFaqs
            ->concat($publishedFaqs)
            ->unique(fn ($faq) => $faq->question)
            ->values();

        return view('public.faq', [
            'faqs' => $faqs->groupBy(fn ($faq) => $faq->category ?: 'General'),
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
