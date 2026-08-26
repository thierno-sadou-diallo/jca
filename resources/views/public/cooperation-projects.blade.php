<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', [
        'title' => 'Projets de coopération | JCA',
        'description' => 'Projets de coopération internationale portes par JCA: gouvernance, développement territorial, institutions et impact durable.',
    ])
</head>
<body>
    @include('partials.header')
    <main>
        <section class="page-hero page-hero-art is-immersive impact-hero">
            <div>
                <span class="eyebrow">Coopération internationale</span>
                <h1>Projets de coopération</h1>
                <p>Des initiatives structurées avec institutions, gouvernements, organisations et partenaires techniques pour transformer les besoins territoriaux en actions mesurables.</p>
                <div class="hero-actions">
                    <a class="button primary" href="{{ $publicRoute('page.show', 'contact') }}">Demander une collaboration</a>
                    <a class="button ghost" href="{{ $publicRoute('public.partners') }}">Voir les partenaires</a>
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
                <span class="eyebrow">Gouvernance et impact</span>
                <h2>Des projets qui connectent expertise, territoires et partenaires.</h2>
                <p>JCA accompagne la structuration, le pilotage et la valorisation des projets pour rendre chaque collaboration plus lisible et mesurable.</p>
            </div>
            <div class="impact-story-grid">
                <article><span>01</span><strong>Structurer</strong><p>Objectifs, acteurs, calendrier, gouvernance et indicateurs.</p></article>
                <article><span>02</span><strong>Mobiliser</strong><p>Partenaires, ressources, compétences et coordination.</p></article>
                <article><span>03</span><strong>Valoriser</strong><p>Résultats, apprentissages et impact durable.</p></article>
            </div>
        </section>

        <section class="content-band">
            <div class="section-heading">
                <span class="eyebrow">Projets actifs</span>
                <h2>Des programmes pilotes pour relier expertise, financement et impact local.</h2>
            </div>
            <div class="cards-grid">
                @forelse ($projects as $project)
                    <article class="news-card reveal">
                        <img src="{{ $project->image_path ? asset('storage/'.$project->image_path) : asset('images/jca-cooperation.webp') }}" alt="{{ $project->title }}" loading="lazy">
                        <span>{{ $project->sector ?: 'Coopération' }} - {{ $project->country ?: 'International' }}</span>
                        <h3>{{ $project->title }}</h3>
                        <p>{{ $project->description ? str($project->description)->limit(170) : 'Projet actif accompagné par JCA avec une approche orientée résultats, gouvernance et durabilité.' }}</p>
                        @if (! empty($project->indicators))
                            <div class="mini-metrics">
                                @foreach (array_slice($project->indicators, 0, 2) as $metric)
                                    <strong>{{ $metric['value'] ?? '' }}<span>{{ $metric['label'] ?? '' }}</span></strong>
                                @endforeach
                            </div>
                        @endif
                        <small>{{ $project->starts_at?->format('d/m/Y') ?: 'Date à définir' }} - {{ $project->ends_at?->format('d/m/Y') ?: 'En cours' }}</small>
                        <a class="admin-link" href="{{ $publicRoute('public.cooperation-projects.show', $project) }}">Voir le projet</a>
                    </article>
                @empty
                    <article class="empty-state">
                        <h2>Aucun projet actif publié pour le moment.</h2>
                        <p>Les projets de coopération actifs apparaîtront ici après validation par l’équipe JCA.</p>
                    </article>
                @endforelse
            </div>
            <div class="pagination-wrap">
                {{ $projects->links() }}
            </div>
        </section>
    </main>
    @include('partials.footer')
    @include('partials.cookie-banner')
</body>
</html>
