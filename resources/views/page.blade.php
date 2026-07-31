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
                    <h2>Un cabinet qui relie les personnes, les organisations et les opportunités.</h2>
                    <p>Notre travail commence par l’écoute, puis devient une stratégie: comprendre le contexte, structurer le dossier et accompagner chaque étape avec méthode.</p>
                </div>
                <div class="story-gallery" aria-label="Univers JCA">
                    <article>
                        <img src="{{ asset('images/jca-immigration.webp') }}" alt="Mobilité internationale" loading="lazy">
                        <strong>Mobilité</strong>
                    </article>
                    <article>
                        <img src="{{ asset('images/jca-recruitment.webp') }}" alt="Talents internationaux" loading="lazy">
                        <strong>Talents</strong>
                    </article>
                    <article>
                        <img src="{{ asset('images/jca-cooperation.webp') }}" alt="Coopération internationale" loading="lazy">
                        <strong>Impact</strong>
                    </article>
                </div>
            </section>
        @endif

        @if ($slug === 'services')
            <section class="service-expérience">
                <div class="section-heading">
                    <span class="eyebrow">Parcours client</span>
                    <h2>Des services présentés comme un chemin simple a suivre.</h2>
                    <p>Chaque client comprend rapidement quoi faire: demander, déposer, échanger, suivre et avancer.</p>
                </div>
                <div class="service-flow">
                    <article><span>01</span><strong>Diagnostic</strong><p>Clarifier l objectif et les options.</p></article>
                    <article><span>02</span><strong>Dossier</strong><p>Rassembler les pièces et preuves utiles.</p></article>
                    <article><span>03</span><strong>Stratégie</strong><p>Construire un plan réaliste et priorisé.</p></article>
                    <article><span>04</span><strong>Suivi</strong><p>Continuer dans l’espace client sécurisé.</p></article>
                </div>
            </section>
        @endif

        @if (! in_array($slug, ['qui-sommes-nous', 'services'], true))
            <section class="page-visual-ribbon" aria-label="Univers visuel JCA">
                <article>
                    <img src="{{ asset('images/jca-immigration.webp') }}" alt="Accompagnement immigration" loading="lazy">
                    <span>Mobilité</span>
                </article>
                <article>
                    <img src="{{ asset('images/jca-recruitment.webp') }}" alt="Talents internationaux" loading="lazy">
                    <span>Talents</span>
                </article>
                <article>
                    <img src="{{ asset('images/jca-cooperation.webp') }}" alt="Coopération internationale" loading="lazy">
                    <span>Impact</span>
                </article>
            </section>
        @endif

        <section class="content-band">
            <div class="section-heading">
                <span class="eyebrow">Expertise</span>
                <h2>Un accompagnement structuré et confidentiel</h2>
                <p>Chaque demande est analysée selon ses objectifs, ses contraintes documentaires et son horizon international.</p>
            </div>
            <div class="{{ $slug === 'services' ? 'service-icon-grid' : 'cards-grid' }}">
                @foreach ($page['sections'] as $section)
                    <article class="{{ $slug === 'services' ? 'service-icon-card reveal' : 'info-card reveal' }}">
                        @if ($slug === 'services')
                            <span class="service-icon service-icon-{{ $loop->iteration }}" aria-hidden="true"></span>
                        @else
                            <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        @endif
                        <h3>{{ $section[0] }}</h3>
                        <p>{{ $section[1] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        @isset($page['form'])
            <section class="{{ $page['form'] === 'contact' ? 'contact-section' : 'split-section' }}">
                <div class="{{ $page['form'] === 'contact' ? 'contact-profile' : '' }}">
                    <span class="eyebrow">{{ $page['form'] === 'consultation' ? 'Demande' : 'Contact JCA' }}</span>
                    <h2>{{ $page['form'] === 'consultation' ? 'Planifier une consultation' : 'Parlons de votre projet international.' }}</h2>
                    @if ($page['form'] === 'contact')
                        <p>JCA répond aux particuliers, entreprises et organisations qui souhaitent structurer une démarche d’immigration, de mobilité, de recrutement ou de coopération internationale.</p>
                        <div class="contact-cards">
                            <article>
                                <strong>Email</strong>
                                <span><a href="mailto:contact@jcaconseil.com">contact@jcaconseil.com</a></span>
                            </article>
                            <article>
                                <strong>Téléphone / WhatsApp</strong>
                                <span><a href="tel:+221789685116">78 968 51 16</a></span>
                            </article>
                            <article>
                                <strong>Accueil professionnel</strong>
                                <span>Votre demande est orientée vers le bon interlocuteur selon le sujet, le pays concerné et le niveau d’urgence.</span>
                            </article>
                            <article>
                                <strong>Suivi confidentiel</strong>
                                <span>Les informations partagées servent uniquement à comprendre votre besoin et à proposer les prochaines étapes.</span>
                            </article>
                            <article>
                                <strong>Partenariats</strong>
                                <span>Institutions, employeurs et organisations peuvent présenter une collaboration ou un projet à développer.</span>
                            </article>
                        </div>
                    @else
                        <p>Présentez votre situation à JCA afin de recevoir une première orientation claire et les étapes utiles pour avancer.</p>
                        <ul class="check-list">
                            <li>Analyse du besoin et du contexte international</li>
                            <li>Documents utiles pour immigration, emploi, partenariat ou projet</li>
                            <li>Réponse de suivi avec les prochaines étapes recommandées</li>
                        </ul>
                    @endif
                </div>
                <form class="lead-form" method="post" action="{{ route('lead-requests.store') }}" enctype="multipart/form-data" data-lead-form>
                    @csrf
                    <input type="hidden" name="source" value="{{ $page['form'] }}">
                    <input type="hidden" name="page_slug" value="{{ $slug }}">
                    <label class="honeypot" aria-hidden="true">Site web<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    <label>Nom complet<input type="text" name="name" required></label>
                    <label>Email<input type="email" name="email" required></label>
                    <label>Téléphone / WhatsApp<input type="tel" name="phone"></label>
                    <label>Motif
                        <select name="topic">
                            <option>Immigration</option>
                            <option>Recrutement international</option>
                            <option>Coopération internationale</option>
                            <option>Partenariat</option>
                            <option>Consultation stratégique</option>
                        </select>
                    </label>
                    @if ($page['form'] === 'consultation')
                        <label>Date souhaitee<input type="date" name="preferred_date"></label>
                        <label>Canal préféré
                            <select name="preferred_channel">
                                <option>Email</option>
                                <option>WhatsApp</option>
                                <option>Téléphone</option>
                            </select>
                        </label>
                    @endif
                    <label>Documents utiles
                        <input type="file" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    </label>
                    <label>Message<textarea name="message" rows="5" required></textarea></label>
                    <button class="button primary" type="submit" data-submit-label="Envoyer la demande">Envoyer la demande</button>
                    <p class="form-note" role="status" aria-live="polite" data-form-note>
                        @if (session('lead_success'))
                            {{ session('lead_success') }}
                        @endif
                    </p>
                </form>
            </section>
        @endisset

        <section class="cta-band">
            <span class="eyebrow">Prochaine étape</span>
            <h2>Transformez votre projet international en feuille de route claire.</h2>
            <a class="button primary" href="{{ $publicRoute('page.show', 'consultation') }}">Demander une consultation</a>
        </section>
    </main>

    @include('partials.footer')
    @include('partials.cookie-banner')
</body>
</html>
