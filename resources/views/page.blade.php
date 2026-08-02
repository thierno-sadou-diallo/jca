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
        @if ($slug === 'contact')
            <section class="contact-section form-only-page">
                @include('partials.lead-form', [
                    'formSource' => 'contact',
                    'pageSlug' => $slug,
                    'submitLabel' => 'Envoyer la demande',
                    'showAppointmentFields' => false,
                ])
            </section>
        @else
            <section class="page-hero page-hero-art {{ in_array($slug, ['qui-sommes-nous', 'services'], true) ? 'is-immersive' : '' }}">
                <div>
                    <span class="eyebrow">{{ $page['eyebrow'] }}</span>
                    <h1>{{ $page['title'] }}</h1>
                    <p>{{ $page['intro'] }}</p>
                    <div class="hero-actions">
                        <a class="button primary" href="{{ route('public.appointments') }}">Prendre rendez-vous</a>
                        <a class="button ghost" href="{{ $publicRoute('page.show', 'contact') }}">Nous contacter</a>
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
                        <span class="eyebrow">Identite JCA</span>
                        <h2>Un accompagnement clair pour les projets internationaux.</h2>
                        <p>JCA aide chaque client a comprendre ses options, organiser les documents utiles et avancer avec un suivi confidentiel.</p>
                    </div>
                    <div class="story-gallery" aria-label="Univers JCA">
                        <article>
                            <img src="{{ asset('images/jca-immigration.webp') }}" alt="Mobilite internationale" loading="lazy">
                            <strong>Mobilite</strong>
                        </article>
                        <article>
                            <img src="{{ asset('images/jca-recruitment.webp') }}" alt="Talents internationaux" loading="lazy">
                            <strong>Talents</strong>
                        </article>
                        <article>
                            <img src="{{ asset('images/jca-cooperation.webp') }}" alt="Cooperation internationale" loading="lazy">
                            <strong>Partenaires</strong>
                        </article>
                    </div>
                </section>
            @endif

            @if (! in_array($slug, ['qui-sommes-nous', 'services', 'collaboration', 'confidentialite'], true))
                <section class="page-visual-ribbon" aria-label="Univers visuel JCA">
                    <article>
                        <img src="{{ asset('images/jca-immigration.webp') }}" alt="Accompagnement immigration" loading="lazy">
                        <span>Mobilite</span>
                    </article>
                    <article>
                        <img src="{{ asset('images/jca-recruitment.webp') }}" alt="Talents internationaux" loading="lazy">
                        <span>Talents</span>
                    </article>
                    <article>
                        <img src="{{ asset('images/jca-cooperation.webp') }}" alt="Cooperation internationale" loading="lazy">
                        <span>Partenaires</span>
                    </article>
                </section>
            @endif

            @php
                $iconPages = ['services', 'collaboration', 'confidentialite'];
                $usesIcons = in_array($slug, $iconPages, true);
            @endphp

            <section class="content-band {{ $usesIcons ? 'services-minimal-band' : '' }}">
                <div class="section-heading">
                    <span class="eyebrow">{{ $usesIcons ? $page['eyebrow'] : 'Expertise' }}</span>
                    <h2>
                        @if ($slug === 'services')
                            Choisissez le service dont vous avez besoin.
                        @elseif ($slug === 'collaboration')
                            Collaborer avec JCA, simplement.
                        @elseif ($slug === 'confidentialite')
                            Vos informations restent encadrees.
                        @else
                            Un accompagnement structure et confidentiel
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
                            <h3>{{ $section[0] }}</h3>
                            @if (($section[1] ?? '') !== '')
                                <p>{{ $section[1] }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>

                @if ($slug === 'services')
                    <div class="service-trust-card reveal">
                        <span class="service-icon service-icon-5" aria-hidden="true"></span>
                        <div>
                            <span class="eyebrow">Relation de confiance</span>
                            <h3>Espace client sécurisé</h3>
                            <p>Après l’ouverture d’un dossier, le client peut transmettre ses documents, suivre les demandes et conserver les échanges importants dans un espace organisé et confidentiel.</p>
                        </div>
                    </div>
                @endif
            </section>

            @isset($page['form'])
                <section class="split-section">
                    <div>
                        <span class="eyebrow">Demande</span>
                        <h2>Planifier une consultation</h2>
                        <p>Presentez votre situation afin de recevoir une premiere orientation claire et les prochaines etapes utiles.</p>
                        <ul class="check-list">
                            <li>Analyse du besoin et du contexte international</li>
                            <li>Documents utiles pour immigration, emploi, partenariat ou projet</li>
                            <li>Reponse de suivi avec les prochaines etapes recommandees</li>
                        </ul>
                    </div>
                    @include('partials.lead-form', [
                        'formSource' => $page['form'],
                        'pageSlug' => $slug,
                        'submitLabel' => 'Envoyer la demande',
                        'showAppointmentFields' => $page['form'] === 'consultation',
                    ])
                </section>
            @endisset

            <section class="cta-band">
                <span class="eyebrow">Prochaine etape</span>
                <h2>Transformez votre projet international en feuille de route claire.</h2>
                <a class="button primary" href="{{ $publicRoute('page.show', 'consultation') }}">Demander une consultation</a>
            </section>
        @endif
    </main>

    @include('partials.footer')
    @include('partials.cookie-banner')
</body>
</html>
