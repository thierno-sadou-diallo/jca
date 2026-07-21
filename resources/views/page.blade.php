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
                    <a class="button primary" href="{{ route('page.show', 'consultation') }}">Prendre rendez-vous</a>
                    <a class="button ghost" href="{{ route('page.show', 'contact') }}">Nous contacter</a>
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
                    <h2>Un cabinet qui relie les personnes, les organisations et les opportunites.</h2>
                    <p>Notre travail commence par l ecoute, puis devient une strategie: comprendre le contexte, structurer le dossier et accompagner chaque etape avec methode.</p>
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
                        <strong>Impact</strong>
                    </article>
                </div>
            </section>
        @endif

        @if ($slug === 'services')
            <section class="service-experience">
                <div class="section-heading">
                    <span class="eyebrow">Parcours client</span>
                    <h2>Des services presentes comme un chemin simple a suivre.</h2>
                    <p>Chaque client comprend rapidement quoi faire: demander, deposer, echanger, suivre et avancer.</p>
                </div>
                <div class="service-flow">
                    <article><span>01</span><strong>Diagnostic</strong><p>Clarifier l objectif et les options.</p></article>
                    <article><span>02</span><strong>Dossier</strong><p>Rassembler les pieces et preuves utiles.</p></article>
                    <article><span>03</span><strong>Strategie</strong><p>Construire un plan realiste et priorise.</p></article>
                    <article><span>04</span><strong>Suivi</strong><p>Continuer dans l espace client securise.</p></article>
                </div>
            </section>
        @endif

        @if (! in_array($slug, ['qui-sommes-nous', 'services'], true))
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
                    <span>Impact</span>
                </article>
            </section>
        @endif

        <section class="content-band">
            <div class="section-heading">
                <span class="eyebrow">Expertise</span>
                <h2>Un accompagnement structure et confidentiel</h2>
                <p>Chaque demande est analysee selon ses objectifs, ses contraintes documentaires et son horizon international.</p>
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
            <section class="split-section">
                <div>
                    <span class="eyebrow">Demande</span>
                    <h2>{{ $page['form'] === 'consultation' ? 'Planifier une consultation' : 'Envoyer un message' }}</h2>
                    <p>Le formulaire est pret pour une integration CRM, email transactionnel ou espace client Laravel securise.</p>
                    <ul class="check-list">
                        <li>Protection CSRF Laravel</li>
                        <li>Pieces jointes prevues pour CV, passeport, diplomes et justificatifs</li>
                        <li>Confirmation et notifications a connecter</li>
                    </ul>
                </div>
                <form class="lead-form" method="post" action="{{ route('lead-requests.store') }}" enctype="multipart/form-data" data-lead-form>
                    @csrf
                    <input type="hidden" name="source" value="{{ $page['form'] }}">
                    <input type="hidden" name="page_slug" value="{{ $slug }}">
                    <label class="honeypot" aria-hidden="true">Site web<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    <label>Nom complet<input type="text" name="name" required></label>
                    <label>Email<input type="email" name="email" required></label>
                    <label>Telephone / WhatsApp<input type="tel" name="phone"></label>
                    <label>Motif
                        <select name="topic">
                            <option>Immigration</option>
                            <option>Recrutement international</option>
                            <option>Cooperation internationale</option>
                            <option>Partenariat</option>
                            <option>Consultation strategique</option>
                        </select>
                    </label>
                    @if ($page['form'] === 'consultation')
                        <label>Date souhaitee<input type="date" name="preferred_date"></label>
                        <label>Canal prefere
                            <select name="preferred_channel">
                                <option>Email</option>
                                <option>WhatsApp</option>
                                <option>Telephone</option>
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
            <span class="eyebrow">Prochaine etape</span>
            <h2>Transformez votre projet international en feuille de route claire.</h2>
            <a class="button primary" href="{{ route('page.show', 'consultation') }}">Demander une consultation</a>
        </section>
    </main>

    @include('partials.footer')
    @include('partials.cookie-banner')
</body>
</html>
