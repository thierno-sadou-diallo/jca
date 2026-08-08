<?php

use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AvailabilityController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\CooperationProjectController as AdminCooperationProjectController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\HumanitarianProgramController as AdminHumanitarianProgramController;
use App\Http\Controllers\Admin\ImmigrationCaseController as AdminImmigrationCaseController;
use App\Http\Controllers\Admin\JobApplicationController as AdminJobApplicationController;
use App\Http\Controllers\Admin\JobPostingController;
use App\Http\Controllers\Admin\LeadRequestController as AdminLeadRequestController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\PartnerController as AdminPartnerController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SiteSettingController as AdminSiteSettingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobBoardController;
use App\Http\Controllers\LeadRequestController;
use App\Http\Controllers\PortalAppointmentController;
use App\Http\Controllers\PortalAuthController;
use App\Http\Controllers\PortalDashboardController;
use App\Http\Controllers\PortalDocumentController;
use App\Http\Controllers\PortalMessageController;
use App\Http\Controllers\PortalNotificationController;
use App\Http\Controllers\PortalProfileController;
use App\Http\Controllers\PortalRegisterController;
use App\Http\Controllers\PortalReviewController;
use App\Http\Controllers\PublicContentController;
use App\Http\Controllers\PublicTestimonialController;
use App\Http\Middleware\EnsureAccountIsActive;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Models\Article;
use App\Models\CooperationProject;
use App\Models\HumanitarianProgram;
use App\Models\JobPosting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

$pages = [
    'qui-sommes-nous' => [
        'title' => 'À propos',
        'eyebrow' => 'Cabinet international de conseil',
        'intro' => 'JCA — Juristyle Conseil & Accompagnement — accompagne particuliers, entreprises, institutions et organisations dans leurs projets d’immigration, de mobilité et de développement international.',
        'sections' => [
            ['Notre vision', 'Être une référence internationale du conseil en mobilité et développement.'],
            ['Notre mission', 'Faciliter la mobilité des talents et soutenir le développement durable.'],
            ['Nos valeurs', 'Six engagements qui guident chacune de nos missions.'],
        ],
    ],
    'equipe' => [
        'title' => 'Équipe',
        'eyebrow' => 'Compétences JCA',
        'intro' => 'Une équipe pluridisciplinaire mobilisée autour de la mobilité internationale, du recrutement, de la coopération et du conseil stratégique.',
        'sections' => [
            ['Conseil et orientation', 'Analyse des besoins, clarification des options et accompagnement des décisions importantes.'],
            ['Immigration et mobilité', 'Préparation, organisation et suivi des démarches avec une attention particulière à la qualité des informations.'],
            ['Recrutement international', 'Lecture des besoins employeurs, valorisation des profils et coordination des parcours candidats.'],
            ['Coopération et partenariats', 'Structuration de collaborations avec institutions, organisations, employeurs et partenaires techniques.'],
        ],
    ],
    'accreditations' => [
        'title' => 'Accréditations',
        'eyebrow' => 'Légitimité professionnelle',
        'intro' => 'JCA publie les titres, affiliations et responsabilités professionnelles utiles pour permettre aux clients et partenaires d’identifier clairement le cadre d’intervention du cabinet.',
        'sections' => [
            ['Équipe et titres', 'Les fonctions, diplômes pertinents et champs d’intervention sont présentés de façon transparente afin de situer le rôle de chaque intervenant dans le traitement des dossiers.'],
            ['Licences professionnelles', 'Lorsque des licences, autorisations ou affiliations professionnelles sont requises par une juridiction, JCA les documente et les associe au périmètre exact des services concernés.'],
            ['Vérification', 'Les clients peuvent demander les références vérifiables applicables à leur dossier lorsque celles-ci existent dans un registre public ou un cadre professionnel reconnu.'],
            ['Limites de mandat', 'JCA distingue les services d’accompagnement, de conseil stratégique et de coordination des situations qui exigent l’intervention d’un avocat, consultant réglementé ou autre professionnel autorisé.'],
            ['Témoignages', 'Les retours clients publiés sont sélectionnés avec consentement, modération et souci de confidentialité, sans présenter une expérience individuelle comme garantie de résultat.'],
        ],
    ],
    'services' => [
        'title' => 'Services',
        'eyebrow' => 'Solutions internationales',
        'intro' => 'Un partenaire stratégique pour les projets qui traversent les frontières.',
        'sections' => [
            ['Immigration & mobilité internationale', 'Visas, résidence permanente, regroupement familial et parrainage.'],
            ['Recrutement international', 'Sourcing, sélection et intégration de talents à l’échelle mondiale.'],
            ['Coopération internationale', 'Ponts institutionnels entre gouvernements, ONG et partenaires.'],
            ['Services-conseils stratégiques', 'Accompagnement sur mesure des dirigeants et organisations.'],
        ],
    ],
    'immigration' => [
        'title' => 'Immigration',
        'eyebrow' => 'Dossiers et statuts',
        'intro' => 'JCA accompagne ses clients dans toutes les étapes des procédures d’immigration et de mobilité internationale vers le Canada, l’Europe, les États-Unis et d’autres destinations stratégiques.',
        'sections' => [
            ['Immigration économique et professionnelle', 'Évaluation du profil, stratégie de sélection, préparation documentaire et suivi des programmes adaptés.'],
            ['Permis de travail et permis d’études', 'Accompagnement des étudiants, travailleurs qualifiés, employeurs et familles dans la préparation des demandes.'],
            ['Résidence permanente', 'Structuration du dossier, preuves, formulaires, cohérence du parcours et anticipation des exigences.'],
            ['Réunification familiale', 'Vérification des critères, preuves relationnelles et accompagnement des familles dans les étapes sensibles.'],
            ['Visas temporaires', 'Dossiers visiteurs, invitations, garanties financières, assurances et justificatifs de retour.'],
            ['Mobilité internationale', 'Conseils stratégiques pour employeurs, travailleurs et organisations qui opèrent dans plusieurs territoires.'],
        ],
    ],
    'recrutement-international' => [
        'title' => 'Recrutement international',
        'eyebrow' => 'Talents et employeurs',
        'intro' => 'JCA soutient les employeurs dans la recherche, la sélection, l’intégration et la rétention de talents qualifiés provenant de différents marchés internationaux.',
        'sections' => [
            ['Travailleurs qualifiés', 'Identification de profils spécialisés, présélection, entretiens, vérification des compétences et coordination des étapes.'],
            ['Missions de recrutement', 'Dispositifs adaptés aux besoins sectoriels, campagnes internationales et suivi des cohortes.'],
            ['Pénuries de main-d’œuvre', 'Accompagnement des employeurs pour planifier les besoins, les contrats, la mobilité et l’intégration.'],
            ['Intégration et rétention', 'Préparation administrative, culturelle et logistique pour favoriser une installation durable.'],
            ['Institutions de formation', 'Développement de passerelles entre employeurs, talents et organismes de formation.'],
            ['Portail candidat', 'Parcours clair pour déposer CV, diplômes, langues, disponibilités et préférences professionnelles.'],
        ],
    ],
    'cooperation-internationale' => [
        'title' => 'Coopération internationale',
        'eyebrow' => 'Institutions et projets',
        'intro' => 'JCA accompagne les gouvernements, institutions, organisations et partenaires au développement dans la conception, la mise en œuvre et l’évaluation de programmes à fort impact.',
        'sections' => [
            ['Développement économique et territorial', 'Programmes favorisant l’entrepreneuriat, les chaînes de valeur, l’emploi et la structuration locale.'],
            ['Renforcement des capacités', 'Diagnostic, plan d’action, gouvernance, formation et outils de pilotage institutionnel.'],
            ['Gouvernance et politiques publiques', 'Appui à la formulation, à la coordination et au suivi de programmes publics ou partenariaux.'],
            ['Éducation et formation', 'Projets relies aux compétences, à l’employabilité, à la formation professionnelle et aux transitions de carriere.'],
            ['Mobilisation de ressources', 'Préparation de notes conceptuelles, dossiers de financement et argumentaires de partenariat international.'],
        ],
    ],
    'humanitaire' => [
        'title' => 'Humanitaire',
        'eyebrow' => 'Actions et impact',
        'intro' => 'JCA contribue à la conception et à la réalisation d’initiatives visant l’amélioration durable des conditions de vie des populations vulnérables.',
        'sections' => [
            ['Éducation', 'Initiatives favorisant l’accès à l’éducation, aux compétences et aux opportunités d’apprentissage.'],
            ['Santé communautaire', 'Actions visant l’amélioration des conditions de vie et de la prévention au niveau local.'],
            ['Développement économique local', 'Programmes soutenant l’autonomie, l’emploi, l’entrepreneuriat et les revenus durables.'],
            ['Inclusion sociale', 'Accompagnement des femmes, des jeunes et des groupes vulnérables dans des parcours d’autonomisation.'],
            ['Résilience communautaire', 'Dispositifs capables de renforcer la capacité d’adaptation face aux chocs sociaux et économiques.'],
        ],
    ],
    'actualites' => [
        'title' => 'Actualités',
        'eyebrow' => 'Veille et communiqués',
        'intro' => 'Un espace éditorial pour suivre les réformes, opportunités, conseils et communiqués autour de la mobilité internationale.',
        'sections' => [
            ['Immigration Canada', 'Analyses des changements de programmes, délais, critères et bonnes pratiques documentaires.'],
            ['Europe et USA', 'Informations utiles sur les parcours, visas, et opportunités internationales.'],
            ['Opportunités', 'Appels à candidatures, recrutements, bourses, partenariats et programmes ouverts.'],
            ['Communiqués', 'Annonces officielles, activités de JCA et informations institutionnelles.'],
        ],
    ],
    'blog' => [
        'title' => 'Blog',
        'eyebrow' => 'Conseils pratiques',
        'intro' => 'Des articles pédagogiques pour aider les candidats, familles, employeurs et partenaires a mieux comprendre leurs options.',
        'sections' => [
            ['Immigration', 'Guides, erreurs courantes, preuves importantes et préparation des dossiers.'],
            ['Travail', 'CV international, entretiens, intégration et attentes des employeurs.'],
            ['Études', 'Choix de programme, admission, budget, visa et préparation du depart.'],
            ['Coopération', 'Montage de projets, financement, gouvernance et partenariats.'],
        ],
    ],
    'emplois' => [
        'title' => 'Emplois',
        'eyebrow' => 'Offres et candidatures',
        'intro' => 'Un espace de publication d’offres avec recherche, filtres métiers et candidature avec CV.',
        'sections' => [
            ['Offres disponibles', 'Opérateurs industriels, santé, technologies, hôtellerie, transport, construction et services.'],
            ['Recherche et filtres', 'Filtrage par pays, secteur, expérience, langue, disponibilité et type de contrat.'],
            ['Candidature', 'Dépôt de CV, diplômes et pièces justificatives pour préqualification.'],
            ['Entreprises', 'Publication d’offres et présentation des besoins de recrutement international.'],
        ],
    ],
    'partenaires' => [
        'title' => 'Partenaires',
        'eyebrow' => 'Réseau international',
        'intro' => 'JCA construit des collaborations avec institutions, universités, entreprises, gouvernements et partenaires techniques.',
        'sections' => [
            ['Institutions', 'Coopération avec acteurs publics et organisations internationales.'],
            ['Universités', 'Passerelles pour les études, la recherche, la formation et la mobilité académique.'],
            ['Entreprises', 'Partenariats pour le recrutement, la formation et l’intégration des talents.'],
            ['Gouvernements', 'Appui aux politiques, programmes et projets territoriaux.'],
        ],
    ],
    'collaboration' => [
        'title' => 'Collaboration',
        'eyebrow' => 'Partenariats',
        'intro' => 'JCA collabore avec les organisations qui souhaitent construire des projets utiles, structurés et internationaux.',
        'sections' => [
            ['Pourquoi collaborer avec nous', 'Une approche claire pour relier besoins, expertise, territoires et opportunités.'],
            ['Types de partenariats', 'Employeurs, institutions, organismes de formation, ONG, universités et partenaires techniques.'],
            ['Comment nous contacter', 'Présentez votre organisation, votre objectif et le type de collaboration recherché via le formulaire.'],
            ['Prochaine étape', 'JCA analyse la demande et propose un échange pour qualifier le cadre de collaboration.'],
        ],
    ],
    'confidentialite' => [
        'title' => 'Confidentialité',
        'eyebrow' => 'Protection des informations',
        'intro' => 'JCA traite les informations transmises avec discrétion, sécurité et uniquement pour comprendre la demande.',
        'sections' => [
            ['Données partagées', 'Les informations envoyées servent à analyser votre besoin et préparer une réponse adaptée.'],
            ['Documents', 'Les fichiers transmis sont utilisés uniquement pour l’étude du dossier ou de la demande.'],
            ['Accès limité', 'Les informations sont consultées par les personnes concernées par le traitement de votre demande.'],
            ['Suivi confidentiel', 'Les échanges restent encadrés par une logique de discrétion professionnelle et de protection des données.'],
        ],
    ],
    'faq' => [
        'title' => 'FAQ',
        'eyebrow' => 'Questions fréquentes',
        'intro' => 'Les réponses essentielles avant une consultation ou le dépôt d’un dossier.',
        'sections' => [
            ['Combien de temps dure une procédure ?', 'Les délais dépendent du pays, du programme, de la qualité du dossier et des volumes de traitement.'],
            ['JCA garantit-il le visa ?', 'Aucun cabinet sérieux ne peut garantir une décision administrative. JCA sécurise la stratégie et la présentation du dossier.'],
            ['Puis-je déposer un CV sans offre ?', 'Oui. Le profil peut être conservé pour des opportunités compatibles avec les besoins des employeurs partenaires.'],
            ['Les entreprises peuvent-elles publier une offre ?', 'Oui. Un formulaire dédié permet de qualifier le besoin et d’organiser une mission de recrutement.'],
        ],
    ],
    'contact' => [
        'title' => 'Contact',
        'eyebrow' => 'Contact',
        'intro' => 'Envoyez votre demande à JCA.',
        'sections' => [
        ],
        'form' => 'contact',
    ],
    'consultation' => [
        'title' => 'Consultation',
        'eyebrow' => 'Rendez-vous stratégique',
        'intro' => 'Planifiez une consultation pour clarifier votre situation, identifier vos options et recevoir une feuille de route.',
        'sections' => [
            ['Choisir le motif', 'Immigration, visa, recrutement, projet, partenariat ou accompagnement stratégique.'],
            ['Calendrier', 'Sélection de créneau et confirmation par email ou WhatsApp.'],
            ['Paiement', 'Les frais applicables sont confirmés avant engagement et les paiements sont traités via les canaux validés par JCA.'],
            ['Confirmation', 'Résumé de la demande, documents requis et prochaines étapes.'],
        ],
        'form' => 'consultation',
    ],
];

$legalPages = [
    'mentions-legales' => [
        'title' => 'Mentions légales',
        'description' => 'Informations légales relatives à l’éditeur du site JCA, a son hébergement et aux responsabilités de publication.',
        'sections' => [
            [
                'title' => 'Éditeur du site',
                'paragraphs' => [
                    'Le site jcaconseil.com est édité par JCA. Les informations administratives complètes de l’entité, son adresse officielle, son immatriculation et les licences professionnelles applicables sont tenues à jour par la direction et communiquées aux clients selon le cadre juridique pertinent.',
                    'Responsable de publication: direction JCA. Contact: contact@jcaconseil.com.',
                ],
            ],
            [
                'title' => 'Hébergement',
                'paragraphs' => [
                    'Le site est exploité sur une infrastructure cloud sécurisée. Les informations complètes de l’hébergeur de production sont conservées dans le dossier technique du site et peuvent être communiquées sur demande légitime.',
                ],
            ],
            [
                'title' => 'Responsabilité',
                'paragraphs' => [
                    'Les contenus publiés par JCA sont fournis à titre informatif et ne constituent pas une garantie de résultat administratif, d’obtention de visa, de permis, d’emploi ou de financement.',
                    'Les décisions finales relèvent toujours des autorités compétentes, des employeurs, institutions ou partenaires concernés.',
                ],
            ],
        ],
    ],
    'politique-confidentialite' => [
        'title' => 'Politique de confidentialité',
        'description' => 'Politique de traitement des données personnelles reçueillies par JCA via le site public et l’espace client.',
        'sections' => [
            [
                'title' => 'Données collectées',
                'paragraphs' => [
                    'JCA peut collecter les données d’identification, coordonnées, pays, ville, type de client, informations de projet, CV, pièces justificatives, documents d’identité, messages, candidatures, rendez-vous, adresse IP et données techniques de navigation.',
                ],
                'items' => [
                    'Identité et coordonnées : nom, email, téléphone, pays, ville, organisation.',
                    'Données de dossier : motif, messages, statut, notes de suivi et documents transmis.',
                    'Documents sensibles : CV, diplômes, justificatifs, pièces d’identité et fichiers utiles au traitement.',
                    'Données techniques: IP, navigateur, journaux de sécurité et horodatages.',
                ],
            ],
            [
                'title' => 'Finalites',
                'paragraphs' => [
                    'Ces données servent à répondre aux demandes, évaluer les projets, organiser les consultations, gérer les candidatures, suivre les dossiers dans l’espace client, sécuriser les accès et respecter les obligations légales ou contractuelles applicables.',
                ],
            ],
            [
                'title' => 'Conservation',
                'paragraphs' => [
                    'Les données sont conservées pendant la durée nécessaire au traitement du dossier, puis archivées ou supprimées selon les obligations légales, contractuelles et opérationnelles applicables. Les demandes sans suite sont réexaminées périodiquement afin de limiter la conservation aux besoins réels du service.',
                ],
            ],
            [
                'title' => 'Sous-traitants',
                'paragraphs' => [
                    'Les sous-traitants peuvent inclure l’hébergeur du site, les services d’email transactionnel, les outils de sauvegarde, les services de paiement et, le cas échéant, un outil d’analytique comme Matomo ou Google Analytics 4 après consentement.',
                    'JCA doit maintenir une liste interne des sous-traitants réellement utilisés et la mettre à jour avant toute communication publique.',
                ],
            ],
            [
                'title' => 'Droits des personnes',
                'paragraphs' => [
                    'Toute personne concernée peut demander l’accès, la rectification, la suppression, la limitation ou la portabilité de ses données lorsque le droit applicable le permet.',
                    'Contact responsable du traitement: contact@jcaconseil.com.',
                ],
            ],
            [
                'title' => 'Cadre juridique',
                'paragraphs' => [
                    'Pour les données de résidents québécois, JCA doit vérifier l’application de la Loi 25 du Québec. Pour les personnes situées dans l’Union européenne ou visées par une offre européenne, JCA doit vérifier l’application du RGPD. Ces cadres ne créent pas exactement les mêmes obligations et doivent être traités distinctement.',
                ],
            ],
        ],
    ],
    'conditions-utilisation' => [
        'title' => 'Conditions générales d’utilisation',
        'description' => 'Conditions d’accès au site JCA et à l’espace client sécurisé.',
        'sections' => [
            [
                'title' => 'Accès à l’espace client',
                'paragraphs' => [
                    'L’espace client permet de déposer des demandes, documents, messages, candidatures et informations de suivi. L’utilisateur s’engage à fournir des informations exactes, complètes et à jour.',
                    'Chaque compte est personnel. L’utilisateur doit protéger son mot de passe et signaler tout accès suspect.',
                ],
            ],
            [
                'title' => 'Documents transmis',
                'paragraphs' => [
                    'Les documents transmis doivent appartenir à l’utilisateur ou être transmis avec autorisation légitime. JCA peut refuser ou demander la correction de fichiers incomplets, illisibles ou non pertinents.',
                ],
            ],
            [
                'title' => 'Absence de garantie de résultat',
                'paragraphs' => [
                    'JCA sécurise la stratégie et la qualité des dossiers, mais ne garantit pas l’obtention d’un visa, d’un permis, d’une admission, d’un emploi, d’un financement ou d’une décision favorable.',
                ],
            ],
            [
                'title' => 'Suspension et sécurité',
                'paragraphs' => [
                    'JCA peut suspendre un compte en cas d’usage abusif, fraude, tentative d’accès non autorisé, usurpation d’identité ou risque pour la sécurité de la plateforme.',
                ],
            ],
        ],
    ],
];

$publishedTestimonialsQuery = function () {
    $query = DB::table('testimonials')->where('is_published', true);

    if (Schema::hasColumn('testimonials', 'locale')) {
        $query->where('locale', app()->getLocale());
    }

    return $query;
};

$homeData = fn () => [
    'pages' => $pages,
    'latestJobs' => Schema::hasTable('job_postings')
        ? JobPosting::where('status', 'published')->latest('published_at')->limit(3)->get()
        : collect(),
    'homeProjects' => Schema::hasTable('cooperation_projects')
        ? CooperationProject::where('status', 'active')->latest('starts_at')->limit(2)->get()
        : collect(),
    'homePrograms' => Schema::hasTable('humanitarian_programs')
        ? HumanitarianProgram::where('status', 'active')->latest()->limit(2)->get()
        : collect(),
    'testimonials' => Schema::hasTable('testimonials')
        ? $publishedTestimonialsQuery()->latest()->limit(3)->get()
        : collect(),
    'testimonialsTotal' => Schema::hasTable('testimonials')
        ? $publishedTestimonialsQuery()->count()
        : 0,
];

Route::get('/', fn () => view('welcome', $homeData()))->name('home');
Route::get('/{locale}', function (string $locale) use ($homeData) {
    abort_unless(in_array($locale, ['fr', 'en'], true), 404);
    session(['locale' => $locale]);
    app()->setLocale($locale);

    return view('welcome', $homeData());
})->whereIn('locale', ['fr', 'en'])->name('localized.home');
Route::get('/emplois', [JobBoardController::class, 'index'])->name('jobs.index');
Route::post('/emplois/{job}/postuler', [JobApplicationController::class, 'store'])
    ->middleware(['auth', EnsureAccountIsActive::class])
    ->name('jobs.apply');
Route::get('/lang/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['fr', 'en'], true), 404);
    session(['locale' => $locale]);

    $previousPath = trim(parse_url(url()->previous(), PHP_URL_PATH) ?: '', '/');
    $normalPath = preg_replace('#^(fr|en)(/|$)#', '', $previousPath) ?? '';
    $targetPath = $locale === 'en' ? trim('en/'.$normalPath, '/') : $normalPath;

    return redirect(url($targetPath));
})->name('locale.switch');

Route::get('/sitemap.xml', function () use ($pages, $legalPages) {
    $staticUrls = collect(array_keys($pages))
        ->reject(fn (string $slug) => $slug === 'emplois')
        ->map(fn (string $slug) => url($slug))
        ->merge([
            route('home'),
            route('jobs.index'),
            route('public.blog'),
            route('public.news'),
            route('public.faq'),
            route('public.testimonials.index'),
            route('public.partners'),
            route('public.cooperation-projects'),
            route('public.humanitarian-programs'),
        ])
        ->merge(collect(array_keys($legalPages))->map(fn (string $slug) => route('legal.show', $slug)))
        ->merge([route('public.portfolio')])
        ->merge(collect(['fr', 'en'])->flatMap(function (string $locale) use ($pages, $legalPages) {
            return collect(array_keys($pages))
                ->reject(fn (string $slug) => $slug === 'emplois')
                ->map(fn (string $slug) => url($locale.'/'.$slug))
                ->merge([
                    url($locale),
                    route('localized.jobs.index', $locale),
                    route('localized.public.blog', $locale),
                    route('localized.public.news', $locale),
                    route('localized.public.faq', $locale),
                    route('localized.public.testimonials.index', $locale),
                    route('localized.public.portfolio', $locale),
                    route('localized.public.partners', $locale),
                    route('localized.public.cooperation-projects', $locale),
                    route('localized.public.humanitarian-programs', $locale),
                ])
                ->merge(collect(array_keys($legalPages))->map(fn (string $slug) => route('localized.legal.show', [$locale, $slug])));
        }))
        ->merge(Schema::hasTable('articles')
            ? Article::where('status', 'published')->pluck('slug')->flatMap(fn (string $slug) => [
                route('public.articles.show', $slug),
                route('localized.public.articles.show', ['fr', $slug]),
                route('localized.public.articles.show', ['en', $slug]),
            ])
            : collect())
        ->merge(Schema::hasTable('job_postings')
            ? JobPosting::where('status', 'published')->pluck('slug')->flatMap(fn (string $slug) => [
                route('jobs.index'),
                route('localized.jobs.index', 'fr'),
                route('localized.jobs.index', 'en'),
            ])
            : collect())
        ->merge(Schema::hasTable('cooperation_projects')
            ? CooperationProject::where('status', 'active')->pluck('slug')->flatMap(fn (string $slug) => [
                route('public.cooperation-projects.show', $slug),
                route('localized.public.cooperation-projects.show', ['fr', $slug]),
                route('localized.public.cooperation-projects.show', ['en', $slug]),
            ])
            : collect())
        ->merge(Schema::hasTable('humanitarian_programs')
            ? HumanitarianProgram::where('status', 'active')->pluck('slug')->flatMap(fn (string $slug) => [
                route('public.humanitarian-programs.show', $slug),
                route('localized.public.humanitarian-programs.show', ['fr', $slug]),
                route('localized.public.humanitarian-programs.show', ['en', $slug]),
            ])
            : collect())
        ->unique()
        ->values();

    return response()
        ->view('sitemap', ['urls' => $staticUrls])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::post('/demandes', [LeadRequestController::class, 'store'])
    ->middleware('throttle:lead-requests')
    ->name('lead-requests.store');
Route::post('/temoignages', [PublicTestimonialController::class, 'store'])
    ->middleware('throttle:lead-requests')
    ->name('public.testimonials.store');
Route::get('/temoignages', function () use ($publishedTestimonialsQuery) {
    return view('public.testimonials', [
        'testimonials' => Schema::hasTable('testimonials')
            ? $publishedTestimonialsQuery()->latest()->paginate(9)
            : collect(),
    ]);
})->name('public.testimonials.index');
Route::get('/blog', [PublicContentController::class, 'blog'])->name('public.blog');
Route::get('/actualites', [PublicContentController::class, 'news'])->name('public.news');
Route::get('/articles/{article:slug}', [PublicContentController::class, 'article'])->name('public.articles.show');
Route::get('/faq', [PublicContentController::class, 'faq'])->name('public.faq');
Route::get('/dossier-collaboration', function () {
    $settings = \App\Models\SiteSetting::publicValues();
    $path = $settings['collaboration_document_path'] ?? '';

    abort_if($path === '' || ! Storage::disk('public')->exists($path), 404);

    return Storage::disk('public')->download(
        $path,
        $settings['collaboration_document_name'] ?: 'dossier-collaboration-jca.pdf',
    );
})->name('public.collaboration-document.download');

Route::get('/portfolio', [\App\Http\Controllers\PublicPortfolioController::class, 'index'])
    ->name('public.portfolio');

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('portfolio', \App\Http\Controllers\Admin\PortfolioItemController::class)
            ->except(['show'])
            ->parameters(['portfolio' => 'portfolio']);
    });
Route::get('/partenaires', [PublicContentController::class, 'partners'])->name('public.partners');
Route::get('/projets-cooperation', [PublicContentController::class, 'cooperationProjects'])->name('public.cooperation-projects');
Route::get('/projets-cooperation/{project:slug}', [PublicContentController::class, 'cooperationProject'])->name('public.cooperation-projects.show');
Route::get('/programmes-humanitaires', [PublicContentController::class, 'humanitarianPrograms'])->name('public.humanitarian-programs');
Route::get('/programmes-humanitaires/{program:slug}', [PublicContentController::class, 'humanitarianProgram'])->name('public.humanitarian-programs.show');
Route::get('/legal/{slug}', function (string $slug) use ($legalPages) {
    abort_unless(isset($legalPages[$slug]), 404);

    return view('legal.show', [
        'legalPage' => $legalPages[$slug],
        'slug' => $slug,
    ]);
})->name('legal.show');

Route::prefix('{locale}')
    ->whereIn('locale', ['fr', 'en'])
    ->group(function () use ($legalPages, $publishedTestimonialsQuery): void {
        $setLocale = function (string $locale): void {
            session(['locale' => $locale]);
            app()->setLocale($locale);
        };

        Route::get('/emplois', function (string $locale, JobBoardController $controller) use ($setLocale) {
            $setLocale($locale);

            return $controller->index(request());
        })->name('localized.jobs.index');

        Route::get('/blog', function (string $locale, PublicContentController $controller) use ($setLocale) {
            $setLocale($locale);

            return $controller->blog();
        })->name('localized.public.blog');

        Route::get('/actualites', function (string $locale, PublicContentController $controller) use ($setLocale) {
            $setLocale($locale);

            return $controller->news();
        })->name('localized.public.news');

        Route::get('/articles/{article:slug}', function (string $locale, Article $article, PublicContentController $controller) use ($setLocale) {
            $setLocale($locale);

            return $controller->article($article);
        })->name('localized.public.articles.show');

        Route::get('/faq', function (string $locale, PublicContentController $controller) use ($setLocale) {
            $setLocale($locale);

            return $controller->faq();
        })->name('localized.public.faq');

        Route::get('/temoignages', function (string $locale) use ($setLocale, $publishedTestimonialsQuery) {
            $setLocale($locale);

            return view('public.testimonials', [
                'testimonials' => Schema::hasTable('testimonials')
                    ? $publishedTestimonialsQuery()->latest()->paginate(9)
                    : collect(),
            ]);
        })->name('localized.public.testimonials.index');

        Route::get('/portfolio', function (string $locale, \App\Http\Controllers\PublicPortfolioController $controller) use ($setLocale) {
            $setLocale($locale);

            return $controller->index();
        })->name('localized.public.portfolio');

        Route::get('/partenaires', function (string $locale, PublicContentController $controller) use ($setLocale) {
            $setLocale($locale);

            return $controller->partners();
        })->name('localized.public.partners');

        Route::get('/projets-cooperation', function (string $locale, PublicContentController $controller) use ($setLocale) {
            $setLocale($locale);

            return $controller->cooperationProjects();
        })->name('localized.public.cooperation-projects');

        Route::get('/projets-cooperation/{project:slug}', function (string $locale, CooperationProject $project, PublicContentController $controller) use ($setLocale) {
            $setLocale($locale);

            return $controller->cooperationProject($project);
        })->name('localized.public.cooperation-projects.show');

        Route::get('/programmes-humanitaires', function (string $locale, PublicContentController $controller) use ($setLocale) {
            $setLocale($locale);

            return $controller->humanitarianPrograms();
        })->name('localized.public.humanitarian-programs');

        Route::get('/programmes-humanitaires/{program:slug}', function (string $locale, HumanitarianProgram $program, PublicContentController $controller) use ($setLocale) {
            $setLocale($locale);

            return $controller->humanitarianProgram($program);
        })->name('localized.public.humanitarian-programs.show');

        Route::get('/legal/{slug}', function (string $locale, string $slug) use ($legalPages, $setLocale) {
            $setLocale($locale);
            abort_unless(isset($legalPages[$slug]), 404);

            return view('legal.show', [
                'legalPage' => $legalPages[$slug],
                'slug' => $slug,
            ]);
        })->name('localized.legal.show');
    });
Route::redirect('/login', '/connexion')->name('login');
Route::get('/inscription', [PortalRegisterController::class, 'create'])->name('portal.register');

Route::middleware('guest')->group(function (): void {
    Route::get('/admin/login', [AuthController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'store'])->middleware('throttle:login')->name('admin.login.store');
    Route::get('/connexion', [PortalAuthController::class, 'create'])->name('portal.login');
    Route::post('/connexion', [PortalAuthController::class, 'store'])->middleware('throttle:login')->name('portal.login.store');
    Route::post('/inscription', [PortalRegisterController::class, 'store'])->middleware('throttle:registration')->name('portal.register.store');
});

Route::post('/deconnexion', [PortalAuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('portal.logout');

Route::get('/rendez-vous', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.appointments.index');
        }

        return redirect()->to(route('portal.dashboard').'#portal-appointment');
    }

    return redirect()->route('page.show', 'consultation');
})->name('public.appointments');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', EnsureUserIsAdmin::class])
    ->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::get('/utilisateurs', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/utilisateurs/{user}/modifier', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::patch('/utilisateurs/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::get('/clients', [AdminClientController::class, 'index'])->name('clients.index');
        Route::get('/clients/{client}', [AdminClientController::class, 'show'])->name('clients.show');
        Route::patch('/clients/{client}', [AdminClientController::class, 'update'])->name('clients.update');
        Route::get('/demandes', [AdminLeadRequestController::class, 'index'])->name('leads.index');
        Route::get('/demandes/{lead}', [AdminLeadRequestController::class, 'show'])->name('leads.show');
        Route::patch('/demandes/{lead}', [AdminLeadRequestController::class, 'update'])->name('leads.update');
        Route::get('/documents', [AdminDocumentController::class, 'index'])->name('documents.index');
        Route::patch('/documents/{document}', [AdminDocumentController::class, 'update'])->name('documents.update');
        Route::get('/documents/{document}/telecharger', [AdminDocumentController::class, 'download'])->name('documents.download');
        Route::get('/dossiers-immigration', [AdminImmigrationCaseController::class, 'index'])->name('immigration-cases.index');
        Route::get('/dossiers-immigration/creer', [AdminImmigrationCaseController::class, 'create'])->name('immigration-cases.create');
        Route::post('/dossiers-immigration', [AdminImmigrationCaseController::class, 'store'])->name('immigration-cases.store');
        Route::get('/dossiers-immigration/{immigrationCase}', [AdminImmigrationCaseController::class, 'show'])->name('immigration-cases.show');
        Route::patch('/dossiers-immigration/{immigrationCase}', [AdminImmigrationCaseController::class, 'update'])->name('immigration-cases.update');
        Route::get('/avis', [ReviewController::class, 'index'])->name('reviews.index');
        Route::patch('/avis/{review}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/avis/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{client}', [AdminMessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{client}', [AdminMessageController::class, 'store'])->name('messages.store');
        Route::get('/disponibilites', [AvailabilityController::class, 'index'])->name('availability.index');
        Route::post('/disponibilites', [AvailabilityController::class, 'store'])->name('availability.store');
        Route::delete('/disponibilites/{slot}', [AvailabilityController::class, 'destroy'])->name('availability.destroy');
        Route::get('/rendez-vous', [AdminAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/rendez-vous/{appointment}', [AdminAppointmentController::class, 'show'])->name('appointments.show');
        Route::patch('/rendez-vous/{appointment}', [AdminAppointmentController::class, 'update'])->name('appointments.update');
        Route::resource('emplois', JobPostingController::class)
            ->parameters(['emplois' => 'job'])
            ->names('jobs')
            ->except(['show', 'destroy']);
        Route::get('/candidatures', [AdminJobApplicationController::class, 'index'])->name('applications.index');
        Route::get('/candidatures/{application}', [AdminJobApplicationController::class, 'show'])->name('applications.show');
        Route::patch('/candidatures/{application}', [AdminJobApplicationController::class, 'update'])->name('applications.update');
        Route::get('/candidatures/{application}/cv', [AdminJobApplicationController::class, 'download'])->name('applications.download');
        Route::resource('articles', AdminArticleController::class)->except(['show', 'destroy']);
        Route::resource('faqs', AdminFaqController::class)->except(['show', 'destroy']);
        Route::resource('projets-cooperation', AdminCooperationProjectController::class)
            ->parameters(['projets-cooperation' => 'project'])
            ->names('cooperation-projects')
            ->except(['show', 'destroy']);
        Route::resource('programmes-humanitaires', AdminHumanitarianProgramController::class)
            ->parameters(['programmes-humanitaires' => 'program'])
            ->names('humanitarian-programs')
            ->except(['show', 'destroy']);
        Route::resource('partenaires', AdminPartnerController::class)
            ->parameters(['partenaires' => 'partner'])
            ->names('partners')
            ->except(['show', 'destroy']);
        Route::resource('paiements', AdminPaymentController::class)
            ->parameters(['paiements' => 'payment'])
            ->names('payments')
            ->except(['show', 'destroy']);
        Route::get('/statistiques', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/parametres', [AdminSiteSettingController::class, 'edit'])->name('settings.edit');
        Route::patch('/parametres', [AdminSiteSettingController::class, 'update'])->name('settings.update');
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    });

Route::get('/espace', PortalDashboardController::class)
    ->middleware(['auth', EnsureAccountIsActive::class])
    ->name('portal.dashboard');

Route::patch('/espace/profil', [PortalProfileController::class, 'update'])
    ->middleware(['auth', EnsureAccountIsActive::class])
    ->name('portal.profile.update');

Route::post('/espace/documents', [PortalDocumentController::class, 'store'])
    ->middleware(['auth', EnsureAccountIsActive::class])
    ->name('portal.documents.store');

Route::post('/espace/avis', [PortalReviewController::class, 'store'])
    ->middleware(['auth', EnsureAccountIsActive::class])
    ->name('portal.reviews.store');

Route::post('/espace/messages', [PortalMessageController::class, 'store'])
    ->middleware(['auth', EnsureAccountIsActive::class])
    ->name('portal.messages.store');

Route::post('/espace/notifications/lues', [PortalNotificationController::class, 'markAllRead'])
    ->middleware(['auth', EnsureAccountIsActive::class])
    ->name('portal.notifications.read');

Route::post('/espace/rendez-vous', [PortalAppointmentController::class, 'store'])
    ->middleware(['auth', EnsureAccountIsActive::class])
    ->name('portal.appointments.store');

Route::get('/{slug}', function (string $slug) use ($pages) {
    abort_if($slug === 'equipe', 404);
    abort_unless(isset($pages[$slug]), 404);

    return view('page', [
        'slug' => $slug,
        'page' => $pages[$slug],
        'pages' => $pages,
    ]);
})->name('page.show');

Route::get('/{locale}/{slug}', function (string $locale, string $slug) use ($pages) {
    abort_unless(in_array($locale, ['fr', 'en'], true) && isset($pages[$slug]) && $slug !== 'equipe', 404);
    session(['locale' => $locale]);
    app()->setLocale($locale);

    return view('page', [
        'slug' => $slug,
        'page' => $pages[$slug],
        'pages' => $pages,
    ]);
})->whereIn('locale', ['fr', 'en'])->name('localized.page.show');
