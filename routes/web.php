<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AvailabilityController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\CooperationProjectController as AdminCooperationProjectController;
use App\Http\Controllers\Admin\HumanitarianProgramController as AdminHumanitarianProgramController;
use App\Http\Controllers\Admin\ImmigrationCaseController as AdminImmigrationCaseController;
use App\Http\Controllers\Admin\JobApplicationController as AdminJobApplicationController;
use App\Http\Controllers\Admin\JobPostingController;
use App\Http\Controllers\Admin\LeadRequestController as AdminLeadRequestController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\PartnerController as AdminPartnerController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SiteSettingController as AdminSiteSettingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobBoardController;
use App\Http\Controllers\LeadRequestController;
use App\Http\Controllers\PortalAuthController;
use App\Http\Controllers\PortalAppointmentController;
use App\Http\Controllers\PortalDashboardController;
use App\Http\Controllers\PortalDocumentController;
use App\Http\Controllers\PortalMessageController;
use App\Http\Controllers\PortalNotificationController;
use App\Http\Controllers\PortalProfileController;
use App\Http\Controllers\PortalRegisterController;
use App\Http\Controllers\PortalReviewController;
use App\Http\Controllers\PublicContentController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Models\CooperationProject;
use App\Models\HumanitarianProgram;
use App\Models\JobPosting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;

$pages = [
    'qui-sommes-nous' => [
        'title' => 'Qui sommes-nous',
        'eyebrow' => 'Cabinet international de conseil',
        'intro' => 'JCA est un cabinet international de conseil et d accompagnement specialise en immigration, mobilite internationale, recrutement international, cooperation internationale et developpement durable.',
        'sections' => [
            ['Notre positionnement', 'Nous accompagnons particuliers, entreprises, institutions publiques, organisations internationales, organismes a but non lucratif et investisseurs dans leurs projets a l echelle mondiale.'],
            ['Notre approche', 'JCA combine expertise juridique, comprehension des dynamiques migratoires, connaissance des marches internationaux du travail et maitrise des mecanismes de cooperation.'],
            ['Notre mission', 'Faciliter la mobilite des talents, soutenir la croissance des organisations et contribuer au developpement durable des communautes.'],
            ['Nos valeurs', 'Excellence, integrite, professionnalisme, innovation, inclusion, responsabilite sociale et developpement durable.'],
            ['Notre impact', 'Batir des ponts entre les talents, les organisations et les opportunites pour contribuer a un monde plus ouvert, inclusif et prospere.'],
        ],
    ],
    'services' => [
        'title' => 'Services',
        'eyebrow' => 'Solutions internationales',
        'intro' => 'JCA accompagne les projets de mobilite, d immigration, de recrutement international, de cooperation et de developpement avec des parcours clairs et securises.',
        'sections' => [
            ['Immigration et mobilite', 'Evaluation, strategie, preparation documentaire, depot, suivi et accompagnement des candidats, familles, etudiants et travailleurs.'],
            ['Recrutement international', 'Accompagnement des employeurs et candidats: publication des besoins, prequalification, integration et suivi.'],
            ['Cooperation internationale', 'Montage de projets, partenariats, gouvernance, financement, indicateurs et evaluation.'],
            ['Developpement durable', 'Programmes a impact autour de l inclusion, de l entrepreneuriat, de l education, de la sante et de la resilience.'],
            ['Espace client securise', 'Apres inscription, chaque client peut deposer ses documents, envoyer ses demandes et suivre ses dossiers.'],
            ['Accompagnement strategique', 'Consultations, feuille de route, analyse des risques et coordination des prochaines etapes.'],
        ],
    ],
    'immigration' => [
        'title' => 'Immigration',
        'eyebrow' => 'Dossiers et statuts',
        'intro' => 'JCA accompagne ses clients dans toutes les etapes des procedures d immigration et de mobilite internationale vers le Canada, l Europe, les Etats-Unis et d autres destinations strategiques.',
        'sections' => [
            ['Immigration economique et professionnelle', 'Evaluation du profil, strategie de selection, preparation documentaire et suivi des programmes adaptes.'],
            ['Permis de travail et permis d etudes', 'Accompagnement des etudiants, travailleurs qualifies, employeurs et familles dans la preparation des demandes.'],
            ['Residence permanente', 'Structuration du dossier, preuves, formulaires, coherence du parcours et anticipation des exigences.'],
            ['Reunification familiale', 'Verification des criteres, preuves relationnelles et accompagnement des familles dans les etapes sensibles.'],
            ['Visas temporaires', 'Dossiers visiteurs, invitations, garanties financieres, assurances et justificatifs de retour.'],
            ['Mobilite internationale', 'Conseils strategiques pour employeurs, travailleurs et organisations qui operent dans plusieurs territoires.'],
        ],
    ],
    'recrutement-international' => [
        'title' => 'Recrutement international',
        'eyebrow' => 'Talents et employeurs',
        'intro' => 'JCA soutient les employeurs dans la recherche, la selection, l integration et la retention de talents qualifies provenant de differents marches internationaux.',
        'sections' => [
            ['Travailleurs qualifies', 'Identification de profils specialises, preselection, entretiens, verification des competences et coordination des etapes.'],
            ['Missions de recrutement', 'Dispositifs adaptes aux besoins sectoriels, campagnes internationales et suivi des cohortes.'],
            ['Penuries de main-d oeuvre', 'Accompagnement des employeurs pour planifier les besoins, les contrats, la mobilite et l integration.'],
            ['Integration et retention', 'Preparation administrative, culturelle et logistique pour favoriser une installation durable.'],
            ['Institutions de formation', 'Developpement de passerelles entre employeurs, talents et organismes de formation.'],
            ['Portail candidat', 'Parcours clair pour deposer CV, diplomes, langues, disponibilites et preferences professionnelles.'],
        ],
    ],
    'cooperation-internationale' => [
        'title' => 'Cooperation internationale',
        'eyebrow' => 'Institutions et projets',
        'intro' => 'JCA accompagne les gouvernements, institutions, organisations et partenaires au developpement dans la conception, la mise en oeuvre et l evaluation de programmes a fort impact.',
        'sections' => [
            ['Developpement economique et territorial', 'Programmes favorisant l entrepreneuriat, les chaines de valeur, l emploi et la structuration locale.'],
            ['Renforcement des capacites', 'Diagnostic, plan d action, gouvernance, formation et outils de pilotage institutionnel.'],
            ['Gouvernance et politiques publiques', 'Appui a la formulation, a la coordination et au suivi de programmes publics ou partenariaux.'],
            ['Education et formation', 'Projets relies aux competences, a l employabilite, a la formation professionnelle et aux transitions de carriere.'],
            ['Mobilisation de ressources', 'Preparation de notes conceptuelles, dossiers de financement et argumentaires de partenariat international.'],
        ],
    ],
    'developpement-durable' => [
        'title' => 'Developpement durable',
        'eyebrow' => 'Impact et resilience',
        'intro' => 'Des programmes orientes vers l inclusion, l education, la sante communautaire, l entrepreneuriat, l employabilite et la resilience des communautes.',
        'sections' => [
            ['Femmes et jeunes', 'Initiatives de formation, leadership, insertion professionnelle et autonomisation economique.'],
            ['Entrepreneuriat', 'Accompagnement des porteurs de projets, structuration, marche, financement et mentorat.'],
            ['Sante et education', 'Projets a fort impact social, de la conception au reporting.'],
            ['Inclusion', 'Approches sensibles aux vulnerabilites, a l egalite des chances et a la participation locale.'],
            ['Resilience', 'Programmes capables de repondre aux chocs economiques, sociaux et environnementaux.'],
        ],
    ],
    'humanitaire' => [
        'title' => 'Humanitaire',
        'eyebrow' => 'Actions et impact',
        'intro' => 'JCA contribue a la conception et a la realisation d initiatives visant l amelioration durable des conditions de vie des populations vulnerables.',
        'sections' => [
            ['Education', 'Initiatives favorisant l acces a l education, aux competences et aux opportunites d apprentissage.'],
            ['Sante communautaire', 'Actions visant l amelioration des conditions de vie et de la prevention au niveau local.'],
            ['Developpement economique local', 'Programmes soutenant l autonomie, l emploi, l entrepreneuriat et les revenus durables.'],
            ['Inclusion sociale', 'Accompagnement des femmes, des jeunes et des groupes vulnerables dans des parcours d autonomisation.'],
            ['Resilience communautaire', 'Dispositifs capables de renforcer la capacite d adaptation face aux chocs sociaux et economiques.'],
        ],
    ],
    'actualites' => [
        'title' => 'Actualites',
        'eyebrow' => 'Veille et communiques',
        'intro' => 'Un espace editorial pour suivre les reformes, opportunites, conseils et communiques autour de la mobilite internationale.',
        'sections' => [
            ['Immigration Canada', 'Analyses des changements de programmes, delais, criteres et bonnes pratiques documentaires.'],
            ['Europe et USA', 'Informations utiles sur les parcours, visas, et opportunites internationales.'],
            ['Opportunites', 'Appels a candidatures, recrutements, bourses, partenariats et programmes ouverts.'],
            ['Communiques', 'Annonces officielles, activites de JCA et informations institutionnelles.'],
        ],
    ],
    'blog' => [
        'title' => 'Blog',
        'eyebrow' => 'Conseils pratiques',
        'intro' => 'Des articles pedagogiques pour aider les candidats, familles, employeurs et partenaires a mieux comprendre leurs options.',
        'sections' => [
            ['Immigration', 'Guides, erreurs courantes, preuves importantes et preparation des dossiers.'],
            ['Travail', 'CV international, entretiens, integration et attentes des employeurs.'],
            ['Etudes', 'Choix de programme, admission, budget, visa et preparation du depart.'],
            ['Cooperation', 'Montage de projets, financement, gouvernance et partenariats.'],
        ],
    ],
    'emplois' => [
        'title' => 'Emplois',
        'eyebrow' => 'Offres et candidatures',
        'intro' => 'Un espace de publication d offres avec recherche, filtres metiers et candidature avec CV.',
        'sections' => [
            ['Offres disponibles', 'Operateurs industriels, sante, technologies, hotellerie, transport, construction et services.'],
            ['Recherche et filtres', 'Filtrage par pays, secteur, experience, langue, disponibilite et type de contrat.'],
            ['Candidature', 'Depot de CV, diplomes et pieces justificatives pour prequalification.'],
            ['Entreprises', 'Publication d offres et presentation des besoins de recrutement international.'],
        ],
    ],
    'partenaires' => [
        'title' => 'Partenaires',
        'eyebrow' => 'Reseau international',
        'intro' => 'JCA construit des collaborations avec institutions, universites, entreprises, gouvernements et partenaires techniques.',
        'sections' => [
            ['Institutions', 'Cooperation avec acteurs publics et organisations internationales.'],
            ['Universites', 'Passerelles pour les etudes, la recherche, la formation et la mobilite academique.'],
            ['Entreprises', 'Partenariats pour le recrutement, la formation et l integration des talents.'],
            ['Gouvernements', 'Appui aux politiques, programmes et projets territoriaux.'],
        ],
    ],
    'faq' => [
        'title' => 'FAQ',
        'eyebrow' => 'Questions frequentes',
        'intro' => 'Les reponses essentielles avant une consultation ou le depot d un dossier.',
        'sections' => [
            ['Combien de temps dure une procedure?', 'Les delais dependent du pays, du programme, de la qualite du dossier et des volumes de traitement.'],
            ['JCA garantit-il le visa?', 'Aucun cabinet serieux ne peut garantir une decision administrative. JCA securise la strategie et la presentation du dossier.'],
            ['Puis-je deposer un CV sans offre?', 'Oui. Le profil peut etre conserve pour des opportunites compatibles avec les besoins des employeurs partenaires.'],
            ['Les entreprises peuvent-elles publier une offre?', 'Oui. Un formulaire dedie permet de qualifier le besoin et d organiser une mission de recrutement.'],
        ],
    ],
    'contact' => [
        'title' => 'Contact',
        'eyebrow' => 'Parlons de votre projet',
        'intro' => 'Contactez JCA pour une consultation, un partenariat, une demande d immigration, un projet ou un besoin de recrutement.',
        'sections' => [
            ['Canaux', 'Telephone, WhatsApp, Messenger, email et formulaire de contact.'],
            ['Horaires', 'Accueil sur rendez-vous et suivi numerique des demandes.'],
            ['Adresse et carte', 'Zone prevue pour integration Google Maps lorsque l adresse officielle sera confirmee.'],
        ],
        'form' => 'contact',
    ],
    'consultation' => [
        'title' => 'Consultation',
        'eyebrow' => 'Rendez-vous strategique',
        'intro' => 'Planifiez une consultation pour clarifier votre situation, identifier vos options et recevoir une feuille de route.',
        'sections' => [
            ['Choisir le motif', 'Immigration, visa, recrutement, projet, partenariat ou accompagnement strategique.'],
            ['Calendrier', 'Selection de creneau et confirmation par email ou WhatsApp.'],
            ['Paiement', 'Zone prevue pour integration d un module de paiement securise.'],
            ['Confirmation', 'Resume de la demande, documents requis et prochaines etapes.'],
        ],
        'form' => 'consultation',
    ],
];

Route::get('/', fn () => view('welcome', [
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
        ? DB::table('testimonials')->where('is_published', true)->latest()->limit(3)->get()
        : collect(),
]))->name('home');
Route::get('/emplois', [JobBoardController::class, 'index'])->name('jobs.index');
Route::post('/emplois/{job}/postuler', [JobApplicationController::class, 'store'])
    ->middleware('auth')
    ->name('jobs.apply');
Route::get('/lang/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['fr', 'en'], true), 404);
    session(['locale' => $locale]);

    return back();
})->name('locale.switch');

Route::post('/demandes', [LeadRequestController::class, 'store'])->name('lead-requests.store');
Route::get('/blog', [PublicContentController::class, 'blog'])->name('public.blog');
Route::get('/actualites', [PublicContentController::class, 'news'])->name('public.news');
Route::get('/articles/{article:slug}', [PublicContentController::class, 'article'])->name('public.articles.show');
Route::get('/faq', [PublicContentController::class, 'faq'])->name('public.faq');
Route::get('/partenaires', [PublicContentController::class, 'partners'])->name('public.partners');
Route::get('/projets-cooperation', [PublicContentController::class, 'cooperationProjects'])->name('public.cooperation-projects');
Route::get('/projets-cooperation/{project:slug}', [PublicContentController::class, 'cooperationProject'])->name('public.cooperation-projects.show');
Route::get('/programmes-humanitaires', [PublicContentController::class, 'humanitarianPrograms'])->name('public.humanitarian-programs');
Route::get('/programmes-humanitaires/{program:slug}', [PublicContentController::class, 'humanitarianProgram'])->name('public.humanitarian-programs.show');
Route::redirect('/login', '/connexion')->name('login');
Route::get('/inscription', [PortalRegisterController::class, 'create'])->name('portal.register');

Route::middleware('guest')->group(function (): void {
    Route::get('/admin/login', [AuthController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'store'])->name('admin.login.store');
    Route::get('/connexion', [PortalAuthController::class, 'create'])->name('portal.login');
    Route::post('/connexion', [PortalAuthController::class, 'store'])->name('portal.login.store');
    Route::post('/inscription', [PortalRegisterController::class, 'store'])->name('portal.register.store');
});

Route::post('/deconnexion', [PortalAuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('portal.logout');

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
    ->middleware('auth')
    ->name('portal.dashboard');

Route::patch('/espace/profil', [PortalProfileController::class, 'update'])
    ->middleware('auth')
    ->name('portal.profile.update');

Route::post('/espace/documents', [PortalDocumentController::class, 'store'])
    ->middleware('auth')
    ->name('portal.documents.store');

Route::post('/espace/avis', [PortalReviewController::class, 'store'])
    ->middleware('auth')
    ->name('portal.reviews.store');

Route::post('/espace/messages', [PortalMessageController::class, 'store'])
    ->middleware('auth')
    ->name('portal.messages.store');

Route::post('/espace/notifications/lues', [PortalNotificationController::class, 'markAllRead'])
    ->middleware('auth')
    ->name('portal.notifications.read');

Route::post('/espace/rendez-vous', [PortalAppointmentController::class, 'store'])
    ->middleware('auth')
    ->name('portal.appointments.store');

Route::get('/{slug}', function (string $slug) use ($pages) {
    abort_unless(isset($pages[$slug]), 404);

    return view('page', [
        'slug' => $slug,
        'page' => $pages[$slug],
        'pages' => $pages,
    ]);
})->name('page.show');
