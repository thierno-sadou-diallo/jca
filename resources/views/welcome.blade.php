<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('partials.head', [
        'title' => __('JCA | Immigration, recrutement international et coopération'),
        'description' => __('JCA accompagne les candidats, employeurs et partenaires dans leurs projets de mobilité, de recrutement et de coopération internationale.'),
        'structuredData' => [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $siteSettings['brand_name'] ?? 'JCA',
            'url' => url('/'),
            'logo' => asset('images/logo_jca.jpg'),
            'email' => $siteSettings['contact_email'] ?? 'contact@jcaconseil.com',
            'telephone' => $siteSettings['contact_phone'] ?? '78 968 51 16',
            'sameAs' => array_values(array_filter([
                $siteSettings['facebook_url'] ?? null,
                $siteSettings['linkedin_url'] ?? null,
            ])),
        ],
    ])
</head>
<body>
    @include('partials.header')

    <main>
        <section class="hero home-hero">
            <div class="hero-media" aria-hidden="true">
                <img src="{{ asset('images/jca-hero.webp') }}" alt="" loading="eager">
                <img src="{{ asset('images/jca-immigration.webp') }}" alt="" loading="lazy">
                <img src="{{ asset('images/jca-recruitment.webp') }}" alt="" loading="lazy">
                <img src="{{ asset('images/jca-cooperation.webp') }}" alt="" loading="lazy">
            </div>
            <div class="hero-overlay"></div>
            <div class="hero-content home-hero-content">
                <span class="eyebrow">{{ __('Cabinet international') }}</span>
                <h1>JCA</h1>
                <p class="hero-kicker">{{ __('Des projets internationaux mieux pensés, mieux préparés, mieux accompagnés.') }}</p>
                <p class="hero-lede">{{ __('JCA relie les personnes, les employeurs et les partenaires autour de parcours clairs: immigration, mobilité, recrutement international, coopération et conseil stratégique.') }}</p>
                <div class="hero-actions">
                    <a class="button primary" href="{{ route('public.appointments') }}">{{ __('Prendre rendez-vous') }}</a>
                    <a class="button secondary ghost-link" href="{{ $publicRoute('page.show', 'services') }}">{{ __('Explorer les services') }}</a>
                </div>
            </div>
            <div class="home-art-board" aria-label="Univers JCA">
                <article>
                    <img src="{{ asset('images/jca-immigration.webp') }}" alt="Mobilité internationale">
                    <span>{{ __('Mobilité') }}</span>
                </article>
                <article>
                    <img src="{{ asset('images/jca-recruitment.webp') }}" alt="Recrutement international">
                    <span>{{ __('Talents') }}</span>
                </article>
                <article>
                    <img src="{{ asset('images/jca-cooperation.webp') }}" alt="Coopération internationale">
                    <span>{{ __('Coopération') }}</span>
                </article>
            </div>
        </section>

        <section class="home-pathway quick-actions" aria-label="Actions rapides">
            <a href="{{ route('public.appointments') }}"><strong>{{ __('Prendre rendez-vous') }}</strong><span>{{ __('Sans inscription obligatoire, formulaire direct') }}</span></a>
            <a href="{{ $publicRoute('page.show', 'services') }}"><strong>{{ __('Comprendre les services') }}</strong><span>{{ __('Quatre axes d’accompagnement') }}</span></a>
            <a href="{{ $publicRoute('page.show', 'collaboration') }}"><strong>{{ __('Collaborer avec JCA') }}</strong><span>{{ __('Employeurs, institutions et partenaires') }}</span></a>
        </section>

        <section class="home-focus-strip" aria-label="Priorités JCA">
            <article>
                <span class="service-icon service-icon-2" aria-hidden="true"></span>
                <div>
                    <strong>{{ __('Talents') }}</strong>
                    <p>{{ __('Relier les profils aux bons projets.') }}</p>
                </div>
            </article>
            <article>
                <span class="service-icon service-icon-3" aria-hidden="true"></span>
                <div>
                    <strong>{{ __('Partenariats') }}</strong>
                    <p>{{ __('Structurer des collaborations qui tiennent.') }}</p>
                </div>
            </article>
        </section>

        <section class="content-band target-band">
            <div class="section-heading">
                <span class="eyebrow">{{ __('Pour qui') }}</span>
                <h2>{{ __('Un point d’entrée clair pour chaque visiteur.') }}</h2>
            </div>
            <div class="target-grid">
                <a class="target-card reveal" href="{{ $publicRoute('page.show', 'recrutement-international') }}">
                    <span class="target-icon employer-icon" aria-hidden="true"></span>
                    <h3>{{ __('Employeur') }}</h3>
                    <p>{{ __('Recruter, mobiliser et fidéliser les meilleurs talents étrangers avec un parcours clair et professionnel.') }}</p>
                    <span class="target-action">{{ __('Accéder') }}</span>
                </a>
                <a class="target-card reveal" href="{{ $publicRoute('page.show', 'immigration') }}">
                    <span class="target-icon candidate-icon" aria-hidden="true"></span>
                    <h3>{{ __('Candidat') }}</h3>
                    <p>{{ __('Étudiants, travailleurs et investisseurs: comprendre vos options et préparer un projet de mobilité solide.') }}</p>
                    <span class="target-action">{{ __('Accéder') }}</span>
                </a>
                <a class="target-card reveal" href="{{ $publicRoute('page.show', 'collaboration') }}">
                    <span class="target-icon partner-icon" aria-hidden="true"></span>
                    <h3>{{ __('Partenaire') }}</h3>
                    <p>{{ __('Institutions, ONG et gouvernements: structurer des collaborations utiles, mesurables et durables.') }}</p>
                    <span class="target-action">{{ __('Accéder') }}</span>
                </a>
            </div>
        </section>

        @if ($latestJobs->isNotEmpty())
            <section class="content-band">
                <div class="section-heading">
                    <span class="eyebrow">{{ __('Opportunités') }}</span>
                    <h2>{{ __('Offres publiées.') }}</h2>
                </div>
                <div class="cards-grid">
                    @foreach ($latestJobs as $job)
                        <article class="news-card reveal">
                            <span>{{ $job->sector }}</span>
                            <h3>{{ $job->title }}</h3>
                            <p>{{ $job->country }} - {{ str($job->description)->limit(90) }}</p>
                            <a class="admin-link" href="{{ $publicRoute('jobs.index') }}">{{ __('Voir les offres') }}</a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="cta-band">
            <span class="eyebrow">{{ __('Commencer') }}</span>
            <h2>{{ __('Présentez votre projet. JCA vous aide à poser les prochaines étapes.') }}</h2>
            <div class="hero-actions">
                <a class="button primary" href="{{ route('public.appointments') }}">{{ __('Prendre rendez-vous') }}</a>
                <a class="button secondary" href="{{ $publicRoute('page.show', 'contact') }}">{{ __('Contacter JCA') }}</a>
            </div>
        </section>
    </main>

    @include('partials.footer')
    @include('partials.cookie-banner')
</body>
</html>
