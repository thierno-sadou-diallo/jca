<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="JCA est un cabinet international de conseil et d accompagnement specialise en immigration, mobilite internationale, recrutement international, cooperation internationale et developpement durable.">
    <meta property="og:title" content="JCA | Immigration, Recrutement International, Cooperation et Developpement International">
    <meta property="og:description" content="Solutions integrees pour la mobilite des personnes, le recrutement de talents qualifies et les projets de developpement a fort impact.">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('images/logo_jca.jpg') }}">
    <title>JCA | Immigration, Recrutement International et Cooperation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('partials.header')

    <main>
        <section class="hero">
            <div class="hero-media" aria-hidden="true">
                <img src="{{ asset('images/jca-hero.png') }}" alt="" loading="eager">
                <img src="{{ asset('images/jca-immigration.png') }}" alt="" loading="lazy">
                <img src="{{ asset('images/jca-recruitment.png') }}" alt="" loading="lazy">
                <img src="{{ asset('images/jca-cooperation.png') }}" alt="" loading="lazy">
            </div>
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <span class="eyebrow">{{ __('site.home.eyebrow') }}</span>
                <h1>JCA</h1>
                <p class="hero-kicker">Conseil international, mobilite des talents et projets a impact.</p>
                <p class="hero-lede">Immigration, recrutement international, cooperation et developpement: une plateforme claire pour transformer les projets internationaux en parcours suivis.</p>
                <div class="hero-actions">
                    <a class="button primary" href="{{ route('portal.register') }}">Creer mon espace</a>
                    <a class="button secondary" href="{{ route('page.show', 'services') }}">Voir les services</a>
                </div>
                <div class="hero-signals" aria-label="Forces JCA">
                    <span>Mobilite des talents</span>
                    <span>Dossiers suivis</span>
                    <span>Impact international</span>
                </div>
            </div>
            <div class="hero-art" aria-hidden="true">
                <span>Strategy</span>
                <span>Mobility</span>
                <span>Impact</span>
            </div>
        </section>

        <section class="home-pathway">
            <a href="{{ route('page.show', 'qui-sommes-nous') }}"><strong>Comprendre JCA</strong><span>Mission, approche et valeurs</span></a>
            <a href="{{ route('page.show', 'services') }}"><strong>Choisir un service</strong><span>Immigration, recrutement, cooperation</span></a>
            <a href="{{ route('portal.register') }}"><strong>Ouvrir son espace</strong><span>Demandes, documents et suivi</span></a>
        </section>

        <section class="trust-strip" aria-label="Domaines d intervention">
            <span>Immigration</span>
            <span>Mobilite internationale</span>
            <span>Recrutement</span>
            <span>Cooperation</span>
            <span>Developpement durable</span>
            <span>Action humanitaire</span>
        </section>

        <section class="motion-gallery" aria-label="Apercus visuels JCA">
            <article>
                <img src="{{ asset('images/jca-immigration.png') }}" alt="Accompagnement immigration">
                <span>Immigration</span>
                <strong>Dossier clair, pieces utiles, suivi confidentiel.</strong>
            </article>
            <article>
                <img src="{{ asset('images/jca-recruitment.png') }}" alt="Recrutement international">
                <span>Talents</span>
                <strong>Profil, CV, opportunites et mobilite professionnelle.</strong>
            </article>
            <article>
                <img src="{{ asset('images/jca-cooperation.png') }}" alt="Cooperation internationale">
                <span>Impact</span>
                <strong>Projets, partenaires et developpement international.</strong>
            </article>
        </section>

        <section class="intro-section">
            <div class="intro-copy">
                <span class="eyebrow">Cabinet international</span>
                <h2>Un partenaire strategique pour les projets qui traversent les frontieres.</h2>
                <p>Une equipe internationale pour clarifier les options, structurer les dossiers et relier les talents, organisations et opportunites.</p>
            </div>
            <div class="intro-visual reveal" aria-label="Domaines JCA">
                <img src="{{ asset('images/jca-hero.png') }}" alt="Accompagnement international JCA">
                <div class="intro-badges">
                    <span>Analyse</span>
                    <span>Documents</span>
                    <span>Suivi</span>
                </div>
            </div>
        </section>

        <section class="stats-band">
            <article><strong>8</strong><span>Axes d intervention</span></article>
            <article><strong>4</strong><span>Publics accompagnes</span></article>
            <article><strong>360</strong><span>Vision integree</span></article>
            <article><strong>24/7</strong><span>Demandes en ligne</span></article>
        </section>

        <section class="content-band">
            <div class="section-heading">
                <span class="eyebrow">Pourquoi choisir JCA</span>
                <h2>Une expertise multidisciplinaire pour agir avec methode et impact.</h2>
            </div>
            <div class="visual-card-grid">
                <article class="visual-card reveal">
                    <img src="{{ asset('images/jca-immigration.png') }}" alt="Accompagnement immigration et mobilite internationale">
                    <div><span>01</span><h3>Immigration et mobilite</h3><p>Procedures vers le Canada, l Europe, les Etats-Unis et d autres destinations strategiques: permis, visas, residence permanente, reunification familiale et conseils de mobilite.</p></div>
                </article>
                <article class="visual-card reveal">
                    <img src="{{ asset('images/jca-recruitment.png') }}" alt="Recrutement international de talents qualifies">
                    <div><span>02</span><h3>Recrutement international</h3><p>Recherche, selection, integration et retention de talents qualifies pour les employeurs confrontes aux enjeux de penurie de main-d oeuvre.</p></div>
                </article>
                <article class="visual-card reveal">
                    <img src="{{ asset('images/jca-cooperation.png') }}" alt="Cooperation internationale et projets de developpement">
                    <div><span>03</span><h3>Cooperation et developpement</h3><p>Conception, mise en oeuvre et evaluation de projets pour gouvernements, institutions, organisations et partenaires au developpement.</p></div>
                </article>
            </div>
        </section>

        <section class="client-tools">
            <div class="tools-visual reveal">
                <img src="{{ asset('images/jca-recruitment.png') }}" alt="Espace client et suivi des demandes">
            </div>
            <div class="section-heading">
                <span class="eyebrow">Parcours utiles</span>
                <h2>Des outils qui aident vraiment a avancer.</h2>
                <p>Les fonctionnalites sensibles commencent apres inscription, dans un espace client securise.</p>
            </div>
            <div class="tool-grid">
                <a href="{{ route('portal.register') }}"><strong>1. Inscription</strong><span>Creation du compte client</span></a>
                <a href="{{ route('portal.register') }}"><strong>2. Depot</strong><span>Demandes et documents confidentiels</span></a>
                <a href="{{ route('portal.register') }}"><strong>3. Analyse</strong><span>Traitement par l equipe JCA</span></a>
                <a href="{{ route('portal.login') }}"><strong>4. Suivi</strong><span>Reponses, statut et prochaines etapes</span></a>
            </div>
        </section>

        <section class="services-section">
            <div class="section-heading">
                <span class="eyebrow">Nos domaines</span>
                <h2>Un portail complet pour tous les publics cibles.</h2>
            </div>
            <div class="service-list">
                @foreach ([
                    'immigration',
                    'recrutement-international',
                    'cooperation-internationale',
                    'developpement-durable',
                    'humanitaire',
                    'emplois',
                ] as $slug)
                    <a class="service-row reveal" href="{{ route('page.show', $slug) }}">
                        <span class="service-row-icon service-icon-{{ $slug }}" aria-hidden="true"></span>
                        <span>{{ $pages[$slug]['eyebrow'] }}</span>
                        <strong>{{ $pages[$slug]['title'] }}</strong>
                        <em>Consulter</em>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="split-section">
            <div class="split-copy">
                <span class="eyebrow">Fonctionnalites premium</span>
                <h2>Notre approche combine expertise juridique, comprehension des marches et impact social.</h2>
                <p>La force de JCA reside dans sa capacite a relier les dynamiques migratoires, les besoins des organisations, les mecanismes de cooperation et les objectifs de developpement durable. Cette approche permet d offrir des solutions concretes, innovantes et adaptees, avec un accompagnement rigoureux a chaque etape.</p>
            </div>
            <div class="feature-panel reveal">
                <img src="{{ asset('images/jca-cooperation.png') }}" alt="Cooperation internationale et strategie">
                <div class="feature-stack">
                    <span>Expertise juridique et strategique</span>
                    <span>Connaissance des marches internationaux du travail</span>
                    <span>Mobilisation de ressources et partenariats</span>
                    <span>Gouvernance, formation et renforcement des capacites</span>
                    <span>Inclusion sociale, femmes, jeunes et resilience</span>
                </div>
            </div>
        </section>

        <section class="content-band muted">
            <div class="section-heading">
                <span class="eyebrow">Mission et valeurs</span>
                <h2>Des projets porteurs de transformation, d innovation et d impact.</h2>
                <p>JCA batit des ponts entre les talents, les organisations et les opportunites afin de contribuer a un monde plus ouvert, plus inclusif et plus prospere.</p>
            </div>
            <div class="values-grid">
                <span>Excellence</span>
                <span>Integrite</span>
                <span>Professionnalisme</span>
                <span>Innovation</span>
                <span>Inclusion</span>
                <span>Responsabilite sociale</span>
                <span>Developpement durable</span>
            </div>
        </section>

        <section class="partners-section">
            <span class="eyebrow">Partenaires</span>
            <div class="partner-logos" aria-label="Types de partenaires">
                <span>Institutions</span>
                <span>Universites</span>
                <span>Entreprises</span>
                <span>Gouvernements</span>
                <span>ONG</span>
            </div>
        </section>

        <section class="content-band muted">
            <div class="section-heading">
                <span class="eyebrow">Projets actifs</span>
                <h2>Cooperation, action humanitaire et impact visible.</h2>
                <p>Les initiatives publiees depuis l administration remontent automatiquement ici.</p>
            </div>
            <div class="cards-grid">
                @forelse ($homeProjects as $project)
                    <article class="news-card reveal">
                        <img src="{{ $project->image_path ? asset('storage/'.$project->image_path) : asset('images/jca-cooperation.png') }}" alt="{{ $project->title }}" loading="lazy">
                        <span>{{ $project->sector ?: 'Cooperation' }} - {{ $project->country ?: 'International' }}</span>
                        <h3>{{ $project->title }}</h3>
                        <p>{{ $project->description ? str($project->description)->limit(135) : 'Projet de cooperation actif accompagne par JCA.' }}</p>
                        @if (! empty($project->indicators))
                            <div class="mini-metrics">
                                @foreach (array_slice($project->indicators, 0, 2) as $metric)
                                    <strong>{{ $metric['value'] ?? '' }}<span>{{ $metric['label'] ?? '' }}</span></strong>
                                @endforeach
                            </div>
                        @endif
                        <a class="admin-link" href="{{ route('public.cooperation-projects.show', $project) }}">Voir le projet</a>
                    </article>
                @empty
                    <article class="news-card reveal">
                        <span>Cooperation</span>
                        <h3>Structurer un projet institutionnel</h3>
                        <p>JCA accompagne la conception, la gouvernance, la mobilisation de partenaires et le suivi d impact.</p>
                        <a class="admin-link" href="{{ route('public.cooperation-projects') }}">Explorer</a>
                    </article>
                @endforelse

                @forelse ($homePrograms as $program)
                    <article class="news-card reveal">
                        <img src="{{ $program->image_path ? asset('storage/'.$program->image_path) : asset('images/jca-hero.png') }}" alt="{{ $program->title }}" loading="lazy">
                        <span>{{ $program->focus_area ?: 'Humanitaire' }} - {{ $program->country ?: 'International' }}</span>
                        <h3>{{ $program->title }}</h3>
                        <p>{{ $program->description ? str($program->description)->limit(135) : 'Programme humanitaire actif accompagne par JCA.' }}</p>
                        @if (! empty($program->impact_metrics))
                            <div class="mini-metrics">
                                @foreach (array_slice($program->impact_metrics, 0, 2) as $metric)
                                    <strong>{{ $metric['value'] ?? '' }}<span>{{ $metric['label'] ?? '' }}</span></strong>
                                @endforeach
                            </div>
                        @endif
                        <a class="admin-link" href="{{ route('public.humanitarian-programs.show', $program) }}">Voir le programme</a>
                    </article>
                @empty
                    <article class="news-card reveal">
                        <span>Humanitaire</span>
                        <h3>Lancer une initiative sociale</h3>
                        <p>Des programmes utiles pour l inclusion, la formation, l employabilite et la resilience des publics.</p>
                        <a class="admin-link" href="{{ route('public.humanitarian-programs') }}">Explorer</a>
                    </article>
                @endforelse
            </div>
        </section>

        <section class="content-band">
            <div class="section-heading">
                <span class="eyebrow">Opportunites</span>
                <h2>Offres internationales publiees par JCA.</h2>
            </div>
            <div class="cards-grid">
                @forelse ($latestJobs as $job)
                    <article class="news-card reveal"><span>{{ $job->sector }}</span><h3>{{ $job->title }}</h3><p>{{ $job->country }} - {{ str($job->description)->limit(120) }}</p><a class="admin-link" href="{{ route('jobs.index') }}">Voir les offres</a></article>
                @empty
                    <article class="news-card reveal"><span>Recrutement</span><h3>Deposez votre profil international</h3><p>Les opportunites publiees apparaitront ici. Vous pouvez deja presenter votre profil pour prequalification.</p><a class="admin-link" href="{{ route('page.show', 'contact') }}">Deposer un profil</a></article>
                    <article class="news-card reveal"><span>Employeurs</span><h3>Publier un besoin de recrutement</h3><p>JCA qualifie les postes, les profils cibles et les contraintes de mobilite internationale.</p><a class="admin-link" href="{{ route('page.show', 'contact') }}">Nous contacter</a></article>
                    <article class="news-card reveal"><span>Immigration</span><h3>Preparer un dossier solide</h3><p>Les preuves, la coherence du parcours et le respect des criteres restent decisifs.</p><a class="admin-link" href="{{ route('page.show', 'consultation') }}">Demander une consultation</a></article>
                @endforelse
            </div>
        </section>

        <section class="testimonials">
            <div class="section-heading">
                <span class="eyebrow">Temoignages</span>
                <h2>Confiance, clarte, accompagnement.</h2>
            </div>
            <div class="cards-grid">
                @forelse ($testimonials as $testimonial)
                    <blockquote class="quote-card reveal">{{ $testimonial->quote }}<cite>{{ $testimonial->author_name }}{{ $testimonial->organization ? ' - '.$testimonial->organization : '' }}</cite></blockquote>
                @empty
                    <blockquote class="quote-card reveal">JCA nous a aide a structurer une demarche de recrutement international avec methode et discretion.<cite>Entreprise partenaire</cite></blockquote>
                    <blockquote class="quote-card reveal">La consultation a rendu notre projet d immigration beaucoup plus clair et realiste.<cite>Famille accompagnee</cite></blockquote>
                    <blockquote class="quote-card reveal">Une approche professionnelle pour passer de l intention au projet presentable.<cite>Organisation locale</cite></blockquote>
                @endforelse
            </div>
        </section>

        <section class="faq-preview">
            <div>
                <span class="eyebrow">FAQ</span>
                <h2>Questions frequentes</h2>
            </div>
            <details open><summary>JCA garantit-il l obtention d un visa?</summary><p>Non. JCA securise la strategie et la qualite du dossier, mais la decision appartient toujours aux autorites competentes.</p></details>
            <details><summary>Les entreprises peuvent-elles publier une offre?</summary><p>Oui. Le parcours employeur permet de qualifier le besoin et de preparer une mission de recrutement.</p></details>
            <details><summary>Le site peut-il devenir administrable?</summary><p>Oui. La structure Laravel permet d ajouter tableau de bord, articles, emplois, documents et statistiques.</p></details>
        </section>

        <section class="cta-band">
            <span class="eyebrow">Espace client</span>
            <h2>Votre projet merite un espace clair, confidentiel et suivi.</h2>
            <a class="button primary" href="{{ route('portal.register') }}">Creer mon espace</a>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
