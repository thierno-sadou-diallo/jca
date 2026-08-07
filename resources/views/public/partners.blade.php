<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('partials.head', [
        'title' => 'Partenaires | JCA',
        'description' => 'Reseau de partenaires JCA: institutions, universites, entreprises, ONG et gouvernements.',
    ])
</head>
<body>
    @include('partials.header')
    <main>
        <section class="page-hero page-hero-art is-immersive">
            <div>
                <span class="eyebrow">Reseau international</span>
                <h1>Partenaires</h1>
                <p>JCA construit des collaborations avec institutions, universites, entreprises, gouvernements et partenaires techniques.</p>
                <div class="hero-actions">
                    <a class="button primary" href="{{ $publicRoute('page.show', 'contact') }}">Proposer un partenariat</a>
                    <a class="button ghost" href="{{ $publicRoute('public.cooperation-projects') }}">Voir les projets</a>
                </div>
            </div>
            <div class="page-hero-collage" aria-hidden="true">
                <img src="{{ asset('images/jca-cooperation.webp') }}" alt="">
                <img src="{{ asset('images/jca-recruitment.webp') }}" alt="">
                <img src="{{ asset('images/jca-hero.webp') }}" alt="">
            </div>
        </section>

        <section class="impact-story cooperation-story">
            <div>
                <span class="eyebrow">Alliance utile</span>
                <h2>Un reseau qui renforce les projets et ouvre des opportunités.</h2>
                <p>Chaque partenaire ajoute une competence, un territoire, une expertise ou une capacité d’action au service des clients et des programmes JCA.</p>
            </div>
            <div class="impact-story-grid">
                <article><span>01</span><strong>Connecter</strong><p>Relier institutions, talents, entreprises et organisations.</p></article>
                <article><span>02</span><strong>Construire</strong><p>Structurer des projets avec une gouvernance claire.</p></article>
                <article><span>03</span><strong>Amplifier</strong><p>Valoriser les résultats et l’impact des collaborations.</p></article>
            </div>
        </section>

        <section class="content-band">
            <div class="section-heading">
                <span class="eyebrow">Partenaires mis en avant</span>
                <h2>Un reseau oriente impact, mobilité et coopération.</h2>
            </div>
            <div class="cards-grid">
                @forelse ($partners as $partner)
                    <article class="news-card reveal">
                        <span>{{ $partner->type ?: 'Partenaire' }}</span>
                        <h3>{{ $partner->name }}</h3>
                        <p>{{ $partner->country ?: 'International' }} - {{ $partner->summary ?: 'Partenaire du reseau JCA.' }}</p>
                        @if ($partner->website)
                            <a class="admin-link" href="{{ $partner->website }}" target="_blank" rel="noopener">Site web</a>
                        @endif
                    </article>
                @empty
                    <article class="empty-state">
                        <h2>Aucun partenaire publie pour le moment.</h2>
                        <p>Le reseau JCA sera présente ici.</p>
                    </article>
                @endforelse
            </div>
        </section>
    </main>
    @include('partials.footer')
    @include('partials.cookie-banner')
</body>
</html>
