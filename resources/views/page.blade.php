<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('partials.head', [
        'title' => $page['title'].' | JCA',
        'description' => $page['intro'],
    ])
</head>
<body>
    @include('partials.header')

    <main>
        @if ($slug === 'consultation')
            <section class="contact-section form-only-page appointment-form-page">
                @include('partials.lead-form', [
                    'formSource' => 'consultation',
                    'pageSlug' => $slug,
                    'submitLabel' => 'Demander le rendez-vous',
                    'showAppointmentFields' => true,
                ])
            </section>
        @elseif ($slug === 'contact')
            <section class="contact-section contact-business-page">
                <div class="contact-business-card">
                    <a class="brand contact-brand" href="{{ $publicRoute('home') }}">
                        <img class="brand-logo" src="{{ asset('images/logo_off.webp') }}" alt="Logo JCA">
                        <span>
                            <strong>{{ $siteSettings['brand_name'] ?? 'JCA' }}</strong>
                            <small>{{ __($siteSettings['brand_tagline'] ?? 'Immigration, recrutement et coopération') }}</small>
                        </span>
                    </a>
                    <div>
                        <span class="eyebrow">{{ __('Contact JCA') }}</span>
                        <h1>{{ __('Parlons de votre projet international.') }}</h1>
                        <p>{{ __('Présentez votre situation, votre besoin ou votre proposition de collaboration. L’équipe JCA vous orientera vers la bonne prochaine étape.') }}</p>
                    </div>
                    <div class="contact-info-list">
                        <a href="mailto:{{ $siteSettings['contact_email'] ?? 'contact@jcaconseil.com' }}">
                            <strong>{{ __('Email') }}</strong>
                            <span>{{ $siteSettings['contact_email'] ?? 'contact@jcaconseil.com' }}</span>
                        </a>
                        <a href="tel:{{ preg_replace('/\D+/', '', $siteSettings['contact_phone'] ?? '+221789685116') }}">
                            <strong>{{ __('Téléphone / WhatsApp') }}</strong>
                            <span>{{ $siteSettings['contact_phone'] ?? '78 968 51 16' }}</span>
                        </a>
                        <a href="{{ route('public.appointments') }}">
                            <strong>{{ __('Rendez-vous') }}</strong>
                            <span>{{ __('Formulaire direct, sans inscription obligatoire') }}</span>
                        </a>
                    </div>
                </div>
                @include('partials.lead-form', [
                    'formSource' => 'contact',
                    'pageSlug' => $slug,
                    'submitLabel' => 'Envoyer le message',
                    'showAppointmentFields' => false,
                ])
            </section>
        @else
            <section class="page-hero page-hero-art is-immersive {{ $slug === 'qui-sommes-nous' ? 'page-hero-about' : '' }} {{ $slug === 'confidentialite' ? 'page-hero-privacy' : '' }}">
                <div>
                    <span class="eyebrow">{{ __($page['eyebrow']) }}</span>
                    <h1>{{ __($page['title']) }}</h1>
                    <p>{{ __($page['intro']) }}</p>
                    <div class="hero-actions">
                        <a class="button primary" href="{{ route('public.appointments') }}">{{ __('Prendre rendez-vous') }}</a>
                        <a class="button ghost" href="{{ $publicRoute('page.show', 'contact') }}">{{ __('Nous contacter') }}</a>
                    </div>
                </div>

                @if ($slug === 'qui-sommes-nous')
                    <div class="about-hero-signature">
                        <span class="about-orbit about-orbit-1" aria-hidden="true"></span>
                        <span class="about-orbit about-orbit-2" aria-hidden="true"></span>
                        <article class="about-hero-seal">
                            <img src="{{ asset('images/logo_off.webp') }}" alt="">
                            <strong>{{ __('Juristyle Conseil') }}</strong>
                            <small>{{ __('Accompagnement international') }}</small>
                        </article>
                        <article>
                            <strong>{{ __('Rigueur') }}</strong>
                            <small>{{ __('Méthode') }}</small>
                        </article>
                        <article>
                            <strong>{{ __('Mobilité') }}</strong>
                            <small>{{ __('Talents') }}</small>
                        </article>
                    </div>
                @elseif ($slug === 'confidentialite')
                    <div class="privacy-hero-mark">
                        <span class="privacy-ring privacy-ring-1" aria-hidden="true"></span>
                        <span class="privacy-ring privacy-ring-2" aria-hidden="true"></span>
                        <div class="privacy-shield" aria-hidden="true">
                            <span></span>
                        </div>
                        <article>
                            <strong>{{ __('Protection') }}</strong>
                            <small>{{ __('Données encadrées') }}</small>
                        </article>
                        <article>
                            <strong>{{ __('Accès limité') }}</strong>
                            <small>{{ __('Discrétion professionnelle') }}</small>
                        </article>
                    </div>
                @else
                    <div class="page-hero-collage" aria-hidden="true">
                        <img src="{{ asset('images/jca-hero.webp') }}" alt="">
                        <img src="{{ asset('images/jca-immigration.webp') }}" alt="">
                        <img src="{{ asset('images/jca-cooperation.webp') }}" alt="">
                    </div>
                @endif
            </section>

            @if ($slug === 'qui-sommes-nous')
                <section class="story-showcase about-showcase">
                    <div class="story-copy about-copy">
                        <span class="eyebrow">{{ __('À propos') }}</span>
                        <h2>{{ __('Un cabinet international guidé par le sens et la rigueur.') }}</h2>
                        <p class="about-credentials">{{ __('Avocat (Barreau du Québec) / Expert en gouvernance, sécurité et politiques publiques en Afrique de l’Ouest / Gestionnaire de projets internationaux / Auteur') }}</p>
                        <p class="about-signature-text">{{ __('Fort d’une expertise reconnue en immigration, mobilité internationale et développement, Me NDIAYE accompagne depuis des années une clientèle variée à travers le monde — particuliers, entreprises, gouvernements et organisations internationales — dans leurs démarches les plus stratégiques, dans les projets d’immigration, de recrutement internationnal ainsi que dans leur demarches juridique.') }}</p>
                        <div class="about-pillars">
                            <article>
                                <span>{{ __('Notre vision') }}</span>
                                <h3>{{ __('Être une référence internationale du conseil en mobilité et développement.') }}</h3>
                                <p>{{ __('Nous croyons en un monde où le talent et l’ambition peuvent circuler librement, porter des projets utiles, et contribuer à un développement durable et inclusif.') }}</p>
                            </article>
                            <article>
                                <span>{{ __('Notre mission') }}</span>
                                <h3>{{ __('Faciliter la mobilité des talents et soutenir le développement durable.') }}</h3>
                                <p>{{ __('De l’analyse stratégique au suivi opérationnel, nous concevons des solutions sur mesure, alliant expertise juridique, connaissance des marchés et sensibilité aux réalités locales.') }}</p>
                            </article>
                        </div>
                    </div>
                    <div class="about-presentation-board" aria-label="Presentation JCA">
                        <div class="about-board-core">
                            <span>{{ __('JCA') }}</span>
                            <strong>{{ __('Conseil, mobilité et impact') }}</strong>
                        </div>
                        <article>
                            <span class="service-icon service-icon-1" aria-hidden="true"></span>
                            <strong>{{ __('Parcours') }}</strong>
                            <p>{{ __('Clarifier les étapes et sécuriser les décisions importantes.') }}</p>
                        </article>
                        <article>
                            <span class="service-icon service-icon-2" aria-hidden="true"></span>
                            <strong>{{ __('Talents') }}</strong>
                            <p>{{ __('Relier les profils, les organisations et les opportunités.') }}</p>
                        </article>
                        <article>
                            <span class="service-icon service-icon-3" aria-hidden="true"></span>
                            <strong>{{ __('Partenariats') }}</strong>
                            <p>{{ __('Structurer des collaborations utiles, mesurables et durables.') }}</p>
                        </article>
                    </div>
                </section>

                <section class="about-values-section">
                    <div class="section-heading">
                        <span class="eyebrow">{{ __('Nos valeurs') }}</span>
                        <h2>{{ __('Six engagements qui guident chacune de nos missions.') }}</h2>
                    </div>
                    <div class="about-values-grid">
                        <article><span>01</span><h3>{{ __('Excellence') }}</h3><p>{{ __('Une exigence de qualité dans chaque livrable.') }}</p></article>
                        <article><span>02</span><h3>{{ __('Intégrité') }}</h3><p>{{ __('Une éthique irréprochable au service du client.') }}</p></article>
                        <article><span>03</span><h3>{{ __('Professionnalisme') }}</h3><p>{{ __('Des équipes formées aux meilleurs standards internationaux.') }}</p></article>
                        <article><span>04</span><h3>{{ __('Innovation') }}</h3><p>{{ __('Des méthodes agiles au service de résultats concrets.') }}</p></article>
                        <article><span>05</span><h3>{{ __('Inclusion') }}</h3><p>{{ __('La diversité comme moteur de performance.') }}</p></article>
                        <article><span>06</span><h3>{{ __('Responsabilité sociale') }}</h3><p>{{ __('Un impact positif et durable pour les communautés.') }}</p></article>
                    </div>
                </section>
            @endif

            @php
                $visualRibbon = match ($slug) {
                    'confidentialite' => [
                        ['image' => 'jca-hero.webp', 'alt' => 'Cadre confidentiel', 'label' => 'Données'],
                        ['image' => 'jca-immigration.webp', 'alt' => 'Documents confidentiels', 'label' => 'Documents'],
                        ['image' => 'jca-cooperation.webp', 'alt' => 'Suivi professionnel', 'label' => 'Suivi'],
                    ],
                    'immigration' => [
                        ['image' => 'jca-immigration.webp', 'alt' => 'Mobilité internationale', 'label' => 'Dossier'],
                        ['image' => 'jca-hero.webp', 'alt' => 'Analyse du parcours', 'label' => 'Parcours'],
                        ['image' => 'jca-cooperation.webp', 'alt' => 'Préparation internationale', 'label' => 'Préparation'],
                    ],
                    'recrutement-international' => [
                        ['image' => 'jca-recruitment.webp', 'alt' => 'Recrutement international', 'label' => 'Profils'],
                        ['image' => 'jca-hero.webp', 'alt' => 'Employeurs internationaux', 'label' => 'Employeurs'],
                        ['image' => 'jca-immigration.webp', 'alt' => 'Mobilité professionnelle', 'label' => 'Mobilité'],
                    ],
                    'cooperation-internationale' => [
                        ['image' => 'jca-cooperation.webp', 'alt' => 'Coopération internationale', 'label' => 'Projets'],
                        ['image' => 'jca-recruitment.webp', 'alt' => 'Partenaires techniques', 'label' => 'Acteurs'],
                        ['image' => 'jca-hero.webp', 'alt' => 'Impact international', 'label' => 'Impact'],
                    ],
                    default => [],
                };
            @endphp

            @if (! empty($visualRibbon))
                <section class="page-visual-ribbon" aria-label="Univers visuel JCA">
                    @foreach ($visualRibbon as $visual)
                        <article>
                            <img src="{{ asset('images/'.$visual['image']) }}" alt="{{ $visual['alt'] }}" loading="lazy">
                            <span>{{ __($visual['label']) }}</span>
                        </article>
                    @endforeach
                </section>
            @endif

            @if ($slug === 'collaboration')
                <section class="page-context-art collaboration-context">
                    <div>
                        <span class="eyebrow">{{ __('Collaboration') }}</span>
                        <h2>{{ __('Des partenariats lisibles, utiles et bien coordonnés.') }}</h2>
                    </div>
                    <div class="context-art-grid" aria-label="Collaboration JCA">
                        <article>
                            <img src="{{ asset('images/jca-cooperation.webp') }}" alt="Organisations partenaires" loading="lazy">
                            <span>{{ __('Organisations') }}</span>
                        </article>
                        <article>
                            <img src="{{ asset('images/jca-recruitment.webp') }}" alt="Cadre de collaboration" loading="lazy">
                            <span>{{ __('Cadre commun') }}</span>
                        </article>
                        <article>
                            <img src="{{ asset('images/jca-hero.webp') }}" alt="Impact des partenariats" loading="lazy">
                            <span>{{ __('Impact mesurable') }}</span>
                        </article>
                    </div>
                </section>

                <section class="collaboration-contact-showcase">
                    <div class="collaboration-contact-copy">
                        <span class="eyebrow">{{ __('Contact partenaire') }}</span>
                        <h2>{{ __('Parlez-nous de votre organisation.') }}</h2>
                        <p>{{ __('JCA analyse les besoins, le cadre de collaboration et les prochaines étapes avec une approche claire et confidentielle.') }}</p>
                    </div>
                    <div class="collaboration-contact-grid">
                        <a href="mailto:{{ $siteSettings['contact_email'] ?? 'contact@jcaconseil.com' }}">
                            <span>{{ __('Email') }}</span>
                            <strong>{{ $siteSettings['contact_email'] ?? 'contact@jcaconseil.com' }}</strong>
                        </a>
                        <a href="tel:{{ preg_replace('/\D+/', '', $siteSettings['contact_phone'] ?? '+221789685116') }}">
                            <span>{{ __('Téléphone / WhatsApp') }}</span>
                            <strong>{{ $siteSettings['contact_phone'] ?? '78 968 51 16' }}</strong>
                        </a>
                        @if (! empty($siteSettings['collaboration_document_path']))
                            <a class="collaboration-document-card" href="{{ route('public.collaboration-document.download') }}">
                                <span>{{ __('Dossier de collaboration') }}</span>
                                <strong>{{ $siteSettings['collaboration_document_name'] ?: __('Lire et télécharger') }}</strong>
                            </a>
                        @else
                            <a class="collaboration-document-card" href="{{ $publicRoute('page.show', 'contact') }}">
                                <span>{{ __('Dossier de collaboration') }}</span>
                                <strong>{{ __('Disponible sur demande') }}</strong>
                            </a>
                        @endif
                    </div>
                </section>
            @endif

            @php
                $iconPages = ['services', 'collaboration', 'confidentialite'];
                $usesIcons = in_array($slug, $iconPages, true);
                $sectionTranslations = [
                    'Immigration & mobilité internationale' => 'Immigration & international mobility',
                    'Recrutement international' => 'International recruitment',
                    'Coopération internationale' => 'International cooperation',
                    'Services-conseils stratégiques' => 'Strategic advisory services',
                ];
                $serviceDetails = [
                    [
                        'id' => 'immigration-mobilite',
                        'title' => 'Immigration & mobilité internationale',
                        'summary' => 'Visas, résidence permanente, regroupement familial et parrainage.',
                        'description' => 'JCA accompagne les personnes, familles, étudiants, travailleurs et employeurs dans la préparation de parcours migratoires lisibles. L’accompagnement porte sur l’analyse du profil, la cohérence documentaire, les étapes administratives, les risques à anticiper et les prochaines décisions à prendre.',
                        'points' => ['Analyse du profil', 'Préparation documentaire', 'Suivi des étapes sensibles'],
                        'image' => 'jca-immigration.webp',
                    ],
                    [
                        'id' => 'recrutement-international',
                        'title' => 'Recrutement international',
                        'summary' => 'Sourcing, sélection et intégration de talents à l’échelle mondiale.',
                        'description' => 'JCA aide les employeurs à clarifier leurs besoins, identifier des profils qualifiés et organiser un processus de recrutement international plus fiable. Le service relie préqualification, coordination candidat, mobilité professionnelle et préparation de l’intégration.',
                        'points' => ['Sourcing ciblé', 'Préqualification des talents', 'Coordination employeur-candidat'],
                        'image' => 'jca-recruitment.webp',
                    ],
                    [
                        'id' => 'cooperation-internationale',
                        'title' => 'Coopération internationale',
                        'summary' => 'Ponts institutionnels entre gouvernements, ONG et partenaires.',
                        'description' => 'JCA structure des collaborations entre institutions, organisations, employeurs et partenaires techniques. L’objectif est de transformer une intention de partenariat en cadre de travail clair: objectifs, rôles, calendrier, gouvernance et résultats attendus.',
                        'points' => ['Objectifs et gouvernance', 'Partenaires et territoires', 'Résultats mesurables'],
                        'image' => 'jca-cooperation.webp',
                    ],
                    [
                        'id' => 'services-conseils',
                        'title' => 'Services-conseils stratégiques',
                        'summary' => 'Accompagnement sur mesure des dirigeants et organisations.',
                        'description' => 'JCA conseille les dirigeants, organisations et porteurs de projets dans les décisions internationales sensibles. Le service combine diagnostic, feuille de route, analyse des risques, priorisation des actions et préparation des échanges avec les parties prenantes.',
                        'points' => ['Diagnostic stratégique', 'Analyse des risques', 'Feuille de route opérationnelle'],
                        'image' => 'jca-cooperation.webp',
                    ],
                ];
            @endphp

            @if ($slug === 'services')
                <section class="services-excellence">
                    <div class="section-heading">
                        <span class="eyebrow">{{ __('Expertises JCA') }}</span>
                        <h2>{{ __('Un cabinet, quatre domaines d’excellence.') }}</h2>
                        <p>{{ __('Chaque mission est confiée à une équipe pluridisciplinaire, réunie autour d’un objectif : servir vos ambitions avec rigueur, intégrité et une vision internationale.') }}</p>
                    </div>
                    <div class="service-domain-grid">
                        @foreach ($serviceDetails as $service)
                            <article class="service-domain-card reveal">
                                <span class="service-icon service-icon-{{ $loop->iteration }}" aria-hidden="true"></span>
                                <h3>{{ __(app()->getLocale() === 'en' ? ($sectionTranslations[$service['title']] ?? $service['title']) : $service['title']) }}</h3>
                                <p>{{ __($service['summary']) }}</p>
                                <a class="button ghost" href="#{{ $service['id'] }}">{{ __('En savoir plus') }}</a>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section class="service-detail-stack">
                    @foreach ($serviceDetails as $service)
                        <article class="service-detail-panel reveal" id="{{ $service['id'] }}">
                            <div class="service-detail-image">
                                <img src="{{ asset('images/'.$service['image']) }}" alt="{{ $service['title'] }}" loading="lazy">
                            </div>
                            <div class="service-detail-copy">
                                <span class="service-detail-count">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="eyebrow">{{ __('Service') }}</span>
                                <h2>{{ __(app()->getLocale() === 'en' ? ($sectionTranslations[$service['title']] ?? $service['title']) : $service['title']) }}</h2>
                                <p>{{ __($service['description']) }}</p>
                                <ul class="service-detail-points" aria-label="{{ __('Points clés') }}">
                                    @foreach ($service['points'] as $point)
                                        <li>{{ __($point) }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </article>
                    @endforeach
                </section>

                <section class="service-proof-band">
                    <div class="service-trust-card reveal">
                        <span class="service-icon service-icon-5" aria-hidden="true"></span>
                        <div>
                            <span class="eyebrow">{{ __('Relation de confiance') }}</span>
                            <h3>{{ __('Espace client sécurisé') }}</h3>
                            <p>{{ __('Après l’ouverture d’un dossier, le client peut transmettre ses documents, suivre les demandes et conserver les échanges importants dans un espace organisé et confidentiel.') }}</p>
                        </div>
                    </div>
                </section>
            @elseif ($slug !== 'qui-sommes-nous')
                <section class="content-band {{ $usesIcons ? 'services-minimal-band services-minimal-band-'.$slug : '' }}">
                    <div class="section-heading">
                        <span class="eyebrow">{{ __($usesIcons ? $page['eyebrow'] : 'Expertise') }}</span>
                        <h2>
                            @if ($slug === 'collaboration')
                                {{ __('Collaborer avec JCA, simplement.') }}
                            @elseif ($slug === 'confidentialite')
                                {{ __('Vos informations restent encadrees.') }}
                            @else
                                {{ __('Un accompagnement structure et confidentiel') }}
                            @endif
                        </h2>
                    </div>
                    <div class="{{ $usesIcons ? 'service-icon-grid service-icon-grid-'.$slug : 'cards-grid' }}">
                        @foreach ($page['sections'] as $section)
                            <article class="{{ $usesIcons ? 'service-icon-card reveal' : 'info-card reveal' }}">
                                @if ($usesIcons)
                                    <span class="service-card-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span class="service-icon service-icon-{{ $loop->iteration }}" aria-hidden="true"></span>
                                @else
                                    <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                @endif
                                <h3>{{ app()->getLocale() === 'en' ? ($sectionTranslations[$section[0]] ?? __($section[0])) : __($section[0]) }}</h3>
                                @if (($section[1] ?? '') !== '')
                                    <p>{{ __($section[1]) }}</p>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="cta-band">
                <span class="eyebrow">{{ __('Prochaine etape') }}</span>
                <h2>{{ __('Transformez votre projet international en feuille de route claire.') }}</h2>
                <a class="button primary" href="{{ $publicRoute('page.show', 'consultation') }}">{{ __('Demander une consultation') }}</a>
            </section>
        @endif
    </main>

    @include('partials.footer')
    @include('partials.cookie-banner')
</body>
</html>
