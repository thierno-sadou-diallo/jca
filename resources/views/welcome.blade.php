<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('partials.head', [
        'title' => 'JCA | Immigration, recrutement international et cooperation',
        'description' => 'JCA accompagne les candidats, employeurs et partenaires dans les projets d immigration, de mobilite, de recrutement et de cooperation internationale.',
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
        <section class="hero simple-hero">
            <div class="hero-media" aria-hidden="true">
                <img src="{{ asset('images/jca-hero.webp') }}" alt="" loading="eager">
                <img src="{{ asset('images/jca-immigration.webp') }}" alt="" loading="lazy">
                <img src="{{ asset('images/jca-recruitment.webp') }}" alt="" loading="lazy">
            </div>
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <span class="eyebrow">Cabinet international</span>
                <h1>JCA</h1>
                <p class="hero-kicker">Immigration, recrutement international et cooperation.</p>
                <p class="hero-lede">Un accompagnement clair pour evaluer votre situation, preparer les bonnes demarches et avancer avec confiance.</p>
                <div class="hero-actions">
                    <a class="button primary" href="{{ route('public.appointments') }}">Prendre rendez-vous</a>
                    <a class="button secondary ghost-link" href="{{ $publicRoute('page.show', 'services') }}">Voir les services</a>
                    <a class="button secondary ghost-link" href="{{ $publicRoute('page.show', 'contact') }}">Contact</a>
                </div>
            </div>
        </section>

        <section class="home-pathway quick-actions" aria-label="Actions rapides">
            <a href="{{ route('public.appointments') }}"><strong>Prendre rendez-vous</strong><span>Sans inscription obligatoire</span></a>
            <a href="{{ $publicRoute('jobs.index') }}"><strong>Voir les offres</strong><span>Opportunites et candidature</span></a>
            <a href="{{ $publicRoute('page.show', 'contact') }}"><strong>Envoyer une demande</strong><span>Formulaire direct</span></a>
        </section>

        <section class="content-band target-band">
            <div class="section-heading">
                <span class="eyebrow">Cibles</span>
                <h2>Un parcours simple selon votre besoin.</h2>
            </div>
            <div class="target-grid">
                <a class="target-card reveal" href="{{ $publicRoute('page.show', 'recrutement-international') }}">
                    <span class="target-icon employer-icon" aria-hidden="true"></span>
                    <h3>Employeur</h3>
                    <p>Trouver et integrer des talents internationaux.</p>
                </a>
                <a class="target-card reveal" href="{{ $publicRoute('jobs.index') }}">
                    <span class="target-icon candidate-icon" aria-hidden="true"></span>
                    <h3>Candidat</h3>
                    <p>Presenter votre profil et preparer votre mobilite.</p>
                </a>
                <a class="target-card reveal" href="{{ $publicRoute('page.show', 'collaboration') }}">
                    <span class="target-icon partner-icon" aria-hidden="true"></span>
                    <h3>Partenaire</h3>
                    <p>Proposer une collaboration ou un projet international.</p>
                </a>
            </div>
        </section>

        @if ($latestJobs->isNotEmpty())
            <section class="content-band">
                <div class="section-heading">
                    <span class="eyebrow">Opportunites</span>
                    <h2>Offres publiees.</h2>
                </div>
                <div class="cards-grid">
                    @foreach ($latestJobs as $job)
                        <article class="news-card reveal">
                            <span>{{ $job->sector }}</span>
                            <h3>{{ $job->title }}</h3>
                            <p>{{ $job->country }} - {{ str($job->description)->limit(90) }}</p>
                            <a class="admin-link" href="{{ $publicRoute('jobs.index') }}">Voir les offres</a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="cta-band">
            <span class="eyebrow">Contact</span>
            <h2>Presentez votre projet a JCA.</h2>
            <div class="hero-actions">
                <a class="button primary" href="{{ route('public.appointments') }}">Prendre rendez-vous</a>
                <a class="button secondary" href="{{ $publicRoute('page.show', 'contact') }}">Envoyer un message</a>
            </div>
        </section>
    </main>

    @include('partials.footer')
    @include('partials.cookie-banner')
</body>
</html>
