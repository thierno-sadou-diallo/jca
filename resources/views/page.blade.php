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
                        <img class="brand-logo" src="{{ asset('images/logo_jca.jpg') }}" alt="Logo JCA">
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
            <section class="page-hero page-hero-art {{ in_array($slug, ['qui-sommes-nous', 'services'], true) ? 'is-immersive' : '' }}">
                <div>
                    <span class="eyebrow">{{ __($page['eyebrow']) }}</span>
                    <h1>{{ __($page['title']) }}</h1>
                    <p>{{ __($page['intro']) }}</p>
                    <div class="hero-actions">
                        <a class="button primary" href="{{ route('public.appointments') }}">{{ __('Prendre rendez-vous') }}</a>
                        <a class="button ghost" href="{{ $publicRoute('page.show', 'contact') }}">{{ __('Nous contacter') }}</a>
                    </div>
                </div>

                @if (in_array($slug, ['qui-sommes-nous', 'services'], true))
                    <div class="page-hero-collage" aria-hidden="true">
                        <img src="{{ asset('images/jca-hero.webp') }}" alt="">
                        <img src="{{ asset('images/jca-immigration.webp') }}" alt="">
                        <img src="{{ asset('images/jca-cooperation.webp') }}" alt="">
                    </div>
                @endif
            </section>

            @if ($slug === 'qui-sommes-nous')
                <section class="story-showcase">
                    <div class="story-copy">
                        <span class="eyebrow">{{ __('Identite JCA') }}</span>
                        <h2>{{ __('Un accompagnement clair pour les projets internationaux.') }}</h2>
                        <p>{{ __('JCA aide chaque client a comprendre ses options, organiser les documents utiles et avancer avec un suivi confidentiel.') }}</p>
                    </div>
                    <div class="story-gallery" aria-label="Univers JCA">
                        <article>
                            <img src="{{ asset('images/jca-immigration.webp') }}" alt="Mobilite internationale" loading="lazy">
                            <strong>{{ __('Mobilite') }}</strong>
                        </article>
                        <article>
                            <img src="{{ asset('images/jca-recruitment.webp') }}" alt="Talents internationaux" loading="lazy">
                            <strong>{{ __('Talents') }}</strong>
                        </article>
                        <article>
                            <img src="{{ asset('images/jca-cooperation.webp') }}" alt="Cooperation internationale" loading="lazy">
                            <strong>{{ __('Partenaires') }}</strong>
                        </article>
                    </div>
                </section>
            @endif

            @if (! in_array($slug, ['qui-sommes-nous', 'services', 'collaboration', 'confidentialite'], true))
                <section class="page-visual-ribbon" aria-label="Univers visuel JCA">
                    <article>
                        <img src="{{ asset('images/jca-immigration.webp') }}" alt="Accompagnement immigration" loading="lazy">
                        <span>{{ __('Mobilite') }}</span>
                    </article>
                    <article>
                        <img src="{{ asset('images/jca-recruitment.webp') }}" alt="Talents internationaux" loading="lazy">
                        <span>{{ __('Talents') }}</span>
                    </article>
                    <article>
                        <img src="{{ asset('images/jca-cooperation.webp') }}" alt="Cooperation internationale" loading="lazy">
                        <span>{{ __('Partenaires') }}</span>
                    </article>
                </section>
            @endif

            @php
                $iconPages = ['services', 'collaboration', 'confidentialite'];
                $usesIcons = in_array($slug, $iconPages, true);
                $sectionTranslations = [
                    'Immigration & mobilité internationale' => 'Immigration & international mobility',
                    'Recrutement international' => 'International recruitment',
                    'Coopération internationale' => 'International cooperation',
                    'Service-conseils stratégique' => 'Strategic advisory services',
                ];
            @endphp

            <section class="content-band {{ $usesIcons ? 'services-minimal-band' : '' }}">
                <div class="section-heading">
                    <span class="eyebrow">{{ __($usesIcons ? $page['eyebrow'] : 'Expertise') }}</span>
                    <h2>
                        @if ($slug === 'services')
                            {{ __('Choisissez le service dont vous avez besoin.') }}
                        @elseif ($slug === 'collaboration')
                            {{ __('Collaborer avec JCA, simplement.') }}
                        @elseif ($slug === 'confidentialite')
                            {{ __('Vos informations restent encadrees.') }}
                        @else
                            {{ __('Un accompagnement structure et confidentiel') }}
                        @endif
                    </h2>
                </div>
                <div class="{{ $usesIcons ? 'service-icon-grid' : 'cards-grid' }}">
                    @foreach ($page['sections'] as $section)
                        <article class="{{ $usesIcons ? 'service-icon-card reveal' : 'info-card reveal' }}">
                            @if ($usesIcons)
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

                @if ($slug === 'services')
                    <div class="service-trust-card reveal">
                        <span class="service-icon service-icon-5" aria-hidden="true"></span>
                        <div>
                            <span class="eyebrow">{{ __('Relation de confiance') }}</span>
                            <h3>{{ __('Espace client sécurisé') }}</h3>
                            <p>{{ __('Après l’ouverture d’un dossier, le client peut transmettre ses documents, suivre les demandes et conserver les échanges importants dans un espace organisé et confidentiel.') }}</p>
                        </div>
                    </div>
                @endif
            </section>

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
