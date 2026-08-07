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
            'logo' => asset('images/logo_off.webp'),
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
                <p class="hero-kicker hero-kicker-statement">
                    <span class="hero-kicker-accent">{{ __('Faciliter') }}</span> {{ __('la mobilité') }}<br>
                    {{ __('des') }} <em>{{ __('talents') }}</em> {{ __('qui construisent') }}<br>
                    {{ __('le monde de demain.') }}
                </p>
                <p class="hero-lede">{{ __('JCA relie les personnes, les employeurs et les partenaires autour de parcours clairs: immigration, mobilité, recrutement international, coopération et conseil stratégique.') }}</p>
                <div class="hero-actions">
                    <a class="button primary" href="{{ route('public.appointments') }}">{{ __('Prendre rendez-vous') }}</a>
                    <a class="button secondary ghost-link" href="{{ $publicRoute('page.show', 'services') }}">{{ __('Explorer les services') }}</a>
                </div>
            </div>
        </section>

        <section class="home-why-jca" aria-label="Pourquoi choisir JCA Conseil">
            <div class="section-heading">
                <span class="eyebrow">{{ __('Pourquoi choisir') }}</span>
                <h2>{{ __('Pourquoi choisir') }} <em>{{ __('JCA Conseil') }}</em> ?</h2>
            </div>
            <div class="home-why-grid">
                <article><span>01</span><strong>{{ __('Expertise') }} <em>{{ __('Sénégal-Canada') }}</em></strong></article>
                <article><span>02</span><strong>{{ __('Accompagnement') }} <em>{{ __('personnalisé') }}</em></strong></article>
                <article><span>03</span><strong>{{ __('Vision') }} <em>{{ __('stratégique') }}</em></strong></article>
                <article><span>04</span><strong>{{ __('Suivi des') }} <em>{{ __('projets') }}</em></strong></article>
            </div>
        </section>

        <section class="home-focus-strip home-stat-strip" aria-label="{{ __('Statistiques JCA') }}">
            <div class="home-stat-heading">
                <span class="eyebrow">{{ __('Impact JCA') }}</span>
                <h2>{{ __('Des') }} <em>{{ __('chiffres') }}</em> {{ __('qui traduisent notre engagement.') }}</h2>
            </div>
            <ul class="home-stat-list">
                <li>
                    <span class="home-stat-icon service-icon service-icon-1" aria-hidden="true"></span>
                    <strong>{{ __('+ de 50') }}</strong>
                    <span class="home-stat-label">{{ __('dossiers accompagnés') }}</span>
                </li>
                <li>
                    <span class="home-stat-icon service-icon service-icon-5" aria-hidden="true"></span>
                    <strong>{{ __('96%') }}</strong>
                    <span class="home-stat-label">{{ __('taux de satisfaction') }}</span>
                </li>
                <li>
                    <span class="home-stat-icon service-icon service-icon-4" aria-hidden="true"></span>
                    <strong>{{ __('+10 ans') }}</strong>
                    <span class="home-stat-label">{{ __('d’expérience') }}</span>
                </li>
            </ul>
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

        <section class="home-testimonials-section" id="temoignages">
            <div class="testimonial-submit-copy">
                <span class="eyebrow">{{ __('Témoignages') }}</span>
                <h2>{{ __('Des expériences partagées par les visiteurs JCA.') }}</h2>
                <p>{{ __('Les visiteurs peuvent publier directement leur avis afin d’aider d’autres personnes, employeurs et partenaires à mieux comprendre l’accompagnement JCA.') }}</p>
            </div>
            <div class="home-testimonials-layout">
                <div class="home-testimonial-list">
                    @forelse ($testimonials as $testimonial)
                        <figure class="service-testimonial-card reveal">
                            <blockquote>“{{ $testimonial->quote }}”</blockquote>
                            <figcaption>
                                <strong>{{ $testimonial->author_name }}</strong>
                                <span>{{ $testimonial->author_role ?? __('Client JCA') }}{{ filled($testimonial->organization ?? null) ? ' - '.$testimonial->organization : '' }}</span>
                            </figcaption>
                        </figure>
                    @empty
                        <figure class="service-testimonial-card service-testimonial-empty reveal">
                            <blockquote>{{ __('Les témoignages publiés par les visiteurs apparaîtront ici.') }}</blockquote>
                            <figcaption>
                                <strong>{{ __('Avis visiteurs') }}</strong>
                                <span>{{ __('Publication directe') }}</span>
                            </figcaption>
                        </figure>
                    @endforelse
                    @if (($testimonialsTotal ?? 0) > $testimonials->count())
                        <a class="testimonial-more-link" href="{{ $publicRoute('public.testimonials.index') }}">
                            {{ __('Voir tous les témoignages') }}
                        </a>
                    @endif
                </div>
                <form class="lead-form testimonial-form" method="post" action="{{ route('public.testimonials.store') }}">
                    @csrf
                    <label class="honeypot" aria-hidden="true">Site web<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    <div class="form-grid">
                        <label>{{ __('Nom complet') }}<input name="author_name" value="{{ old('author_name') }}" required></label>
                        <label>{{ __('Profil') }}<input name="author_role" value="{{ old('author_role') }}" placeholder="{{ __('Client, candidat, employeur, partenaire') }}"></label>
                    </div>
                    <label>{{ __('Organisation') }}<input name="organization" value="{{ old('organization') }}"></label>
                    <label>{{ __('Votre témoignage') }}<textarea name="quote" rows="5" required>{{ old('quote') }}</textarea></label>
                    @if (session('testimonial_status'))
                        <p class="form-note" data-state="success">{{ session('testimonial_status') }}</p>
                    @elseif ($errors->has('quote') || $errors->has('author_name'))
                        <p class="form-note" data-state="error">{{ $errors->first('quote') ?: $errors->first('author_name') }}</p>
                    @endif
                    <button class="button primary" type="submit">{{ __('Envoyer le témoignage') }}</button>
                </form>
            </div>
        </section>

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
