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

        <section class="content-band target-band">
            <div class="section-heading">
                <span class="eyebrow">{{ __('Pour qui') }}</span>
                <h2>{{ __('Un point d’entrée clair pour chaque visiteur.') }}</h2>
            </div>
            <div class="target-grid">
                <a class="target-card reveal" href="{{ $publicRoute('page.show', 'recrutement-international') }}">
                    <span class="target-icon employer-icon" aria-hidden="true"></span>
                    <h3>{{ __('Employeur') }}</h3>
                    <p>{{ __('Identifier des profils, structurer un besoin et préparer une mobilité professionnelle fiable.') }}</p>
                </a>
                <a class="target-card reveal" href="{{ $publicRoute('jobs.index') }}">
                    <span class="target-icon candidate-icon" aria-hidden="true"></span>
                    <h3>{{ __('Candidat') }}</h3>
                    <p>{{ __('Comprendre vos options, présenter votre profil et avancer avec un accompagnement lisible.') }}</p>
                </a>
                <a class="target-card reveal" href="{{ $publicRoute('page.show', 'collaboration') }}">
                    <span class="target-icon partner-icon" aria-hidden="true"></span>
                    <h3>{{ __('Partenaire') }}</h3>
                    <p>{{ __('Construire une collaboration internationale autour d’objectifs concrets et mesurables.') }}</p>
                </a>
            </div>
        </section>

        <section class="home-manifesto">
            <div class="manifesto-copy">
                <span class="eyebrow">{{ __('Notre boussole') }}</span>
                <h2>{{ __('Un cabinet qui transforme l’intention en feuille de route.') }}</h2>
                <p>{{ __('Chaque projet international commence avec une question simple: comment avancer sans se perdre? JCA apporte une méthode, une lecture professionnelle et un cadre de confiance pour décider, préparer et agir.') }}</p>
            </div>
            <div class="manifesto-grid">
                <article class="reveal">
                    <span class="service-icon service-icon-1" aria-hidden="true"></span>
                    <h3>{{ __('Mission') }}</h3>
                    <p>{{ __('Clarifier les parcours, organiser les démarches et accompagner les décisions importantes.') }}</p>
                </article>
                <article class="reveal">
                    <span class="service-icon service-icon-3" aria-hidden="true"></span>
                    <h3>{{ __('Vision') }}</h3>
                    <p>{{ __('Créer des ponts solides entre talents, organisations et opportunités internationales.') }}</p>
                </article>
                <article class="reveal">
                    <span class="service-icon service-icon-5" aria-hidden="true"></span>
                    <h3>{{ __('Valeurs') }}</h3>
                    <p>{{ __('Exigence, intégrité, confidentialité, écoute et responsabilité dans chaque accompagnement.') }}</p>
                </article>
            </div>
        </section>

        <section class="home-gallery" aria-label="Images JCA">
            <article>
                <img src="{{ asset('images/jca-hero.webp') }}" alt="Accompagnement international JCA" loading="lazy">
                <div><span>{{ __('Conseil') }}</span><strong>{{ __('Voir plus loin, décider plus clairement.') }}</strong></div>
            </article>
            <article>
                <img src="{{ asset('images/jca-recruitment.webp') }}" alt="Talents internationaux" loading="lazy">
                <div><span>{{ __('Talents') }}</span><strong>{{ __('Relier les profils aux bons projets.') }}</strong></div>
            </article>
            <article>
                <img src="{{ asset('images/jca-cooperation.webp') }}" alt="Partenariats internationaux" loading="lazy">
                <div><span>{{ __('Partenariats') }}</span><strong>{{ __('Structurer des collaborations qui tiennent.') }}</strong></div>
            </article>
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
