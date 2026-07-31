<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', [
        'title' => 'Programmes humanitaires | JCA',
        'description' => 'Programmes humanitaires et initiatives sociales JCA: inclusion, formation, employabilite, solidarite et impact humain.',
    ])
</head>
<body>
    @include('partials.header')
    <main>
        <section class="page-hero page-hero-art is-immersive impact-hero">
            <div>
                <span class="eyebrow">Action humanitaire</span>
                <h1>Programmes humanitaires</h1>
                <p>Des actions sociales et solidaires pensees pour accompagner les publics, renforcer l’inclusion et construire des parcours plus dignes, utiles et durables.</p>
                <div class="hero-actions">
                    <a class="button primary" href="{{ $publicRoute('page.show', 'contact') }}">Proposer un partenariat</a>
                    <a class="button ghost" href="{{ route('portal.register') }}">Créer mon espace</a>
                </div>
            </div>
            <div class="page-hero-collage" aria-hidden="true">
                <img src="{{ asset('images/jca-hero.webp') }}" alt="">
                <img src="{{ asset('images/jca-cooperation.webp') }}" alt="">
                <img src="{{ asset('images/jca-immigration.webp') }}" alt="">
            </div>
        </section>

        <section class="impact-story">
            <div>
                <span class="eyebrow">Impact humain</span>
                <h2>Des initiatives qui donnent de la dignite, des compétences et un cap.</h2>
                <p>JCA relie besoins sociaux, partenaires techniques et suivi mesurable pour transformer les intentions solidaires en actions visibles.</p>
            </div>
            <div class="impact-story-grid">
                <article><span>01</span><strong>Identifier</strong><p>Comprendre les publics, les urgences et les ressources locales.</p></article>
                <article><span>02</span><strong>Accompagner</strong><p>Former, orienter, soutenir et documenter les parcours.</p></article>
                <article><span>03</span><strong>Mesurer</strong><p>Suivre les résultats et valoriser l impact humain.</p></article>
            </div>
        </section>

        <section class="content-band">
            <div class="section-heading">
                <span class="eyebrow">Programmes actifs</span>
                <h2>Des initiatives humaines, suivies et mesurables.</h2>
            </div>
            <div class="cards-grid">
                @forelse ($programs as $program)
                    <article class="news-card reveal">
                        <img src="{{ $program->image_path ? asset('storage/'.$program->image_path) : asset('images/jca-hero.webp') }}" alt="{{ $program->title }}" loading="lazy">
                        <span>{{ $program->focus_area ?: 'Impact social' }} - {{ $program->country ?: 'International' }}</span>
                        <h3>{{ $program->title }}</h3>
                        <p>{{ $program->description ? str($program->description)->limit(170) : 'Programme actif accompagne par JCA pour soutenir des besoins sociaux prioritaires.' }}</p>
                        @if (! empty($program->impact_metrics))
                            <div class="mini-metrics">
                                @foreach (array_slice($program->impact_metrics, 0, 2) as $metric)
                                    <strong>{{ $metric['value'] ?? '' }}<span>{{ $metric['label'] ?? '' }}</span></strong>
                                @endforeach
                            </div>
                        @endif
                        <a class="admin-link" href="{{ $publicRoute('public.humanitarian-programs.show', $program) }}">Voir le programme</a>
                    </article>
                @empty
                    <article class="empty-state">
                        <h2>Aucun programme actif publie pour le moment.</h2>
                        <p>Les programmes humanitaires actifs apparaîtront ici après validation par l’équipe JCA.</p>
                    </article>
                @endforelse
            </div>
            {{ $programs->links() }}
        </section>
    </main>
    @include('partials.footer')
    @include('partials.cookie-banner')
</body>
</html>
