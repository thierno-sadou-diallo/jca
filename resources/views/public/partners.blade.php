<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Reseau de partenaires JCA: institutions, universites, entreprises, ONG et gouvernements.">
    <title>Partenaires | JCA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                    <a class="button primary" href="{{ route('page.show', 'contact') }}">Proposer un partenariat</a>
                    <a class="button ghost" href="{{ route('public.cooperation-projects') }}">Voir les projets</a>
                </div>
            </div>
            <div class="page-hero-collage" aria-hidden="true">
                <img src="{{ asset('images/jca-cooperation.png') }}" alt="">
                <img src="{{ asset('images/jca-recruitment.png') }}" alt="">
                <img src="{{ asset('images/jca-hero.png') }}" alt="">
            </div>
        </section>

        <section class="impact-story cooperation-story">
            <div>
                <span class="eyebrow">Alliance utile</span>
                <h2>Un reseau qui renforce les projets et ouvre des opportunites.</h2>
                <p>Chaque partenaire ajoute une competence, un territoire, une expertise ou une capacite d action au service des clients et des programmes JCA.</p>
            </div>
            <div class="impact-story-grid">
                <article><span>01</span><strong>Connecter</strong><p>Relier institutions, talents, entreprises et organisations.</p></article>
                <article><span>02</span><strong>Construire</strong><p>Structurer des projets avec une gouvernance claire.</p></article>
                <article><span>03</span><strong>Amplifier</strong><p>Valoriser les resultats et l impact des collaborations.</p></article>
            </div>
        </section>

        <section class="content-band">
            <div class="section-heading">
                <span class="eyebrow">Partenaires mis en avant</span>
                <h2>Un reseau oriente impact, mobilite et cooperation.</h2>
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
                        <p>Le reseau JCA sera presente ici.</p>
                    </article>
                @endforelse
            </div>
        </section>
    </main>
    @include('partials.footer')
</body>
</html>
