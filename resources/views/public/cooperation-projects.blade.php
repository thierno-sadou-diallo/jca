<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Projets de cooperation - JCA</title>
    <meta name="description" content="Projets de cooperation internationale portes par JCA: gouvernance, developpement territorial, institutions et impact durable.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('partials.header')
    <main>
        <section class="page-hero page-hero-art is-immersive impact-hero">
            <div>
                <span class="eyebrow">Cooperation internationale</span>
                <h1>Projets de cooperation</h1>
                <p>Des initiatives structurees avec institutions, gouvernements, organisations et partenaires techniques pour transformer les besoins territoriaux en actions mesurables.</p>
                <div class="hero-actions">
                    <a class="button primary" href="{{ route('page.show', 'contact') }}">Demander une collaboration</a>
                    <a class="button ghost" href="{{ route('public.partners') }}">Voir les partenaires</a>
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
                <span class="eyebrow">Gouvernance et impact</span>
                <h2>Des projets qui connectent expertise, territoires et partenaires.</h2>
                <p>JCA accompagne la structuration, le pilotage et la valorisation des projets pour rendre chaque collaboration plus lisible et mesurable.</p>
            </div>
            <div class="impact-story-grid">
                <article><span>01</span><strong>Structurer</strong><p>Objectifs, acteurs, calendrier, gouvernance et indicateurs.</p></article>
                <article><span>02</span><strong>Mobiliser</strong><p>Partenaires, ressources, competences et coordination.</p></article>
                <article><span>03</span><strong>Valoriser</strong><p>Resultats, apprentissages et impact durable.</p></article>
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
                        <img src="{{ $project->image_path ? asset('storage/'.$project->image_path) : asset('images/jca-cooperation.png') }}" alt="{{ $project->title }}" loading="lazy">
                        <span>{{ $project->sector ?: 'Cooperation' }} - {{ $project->country ?: 'International' }}</span>
                        <h3>{{ $project->title }}</h3>
                        <p>{{ $project->description ? str($project->description)->limit(170) : 'Projet actif accompagne par JCA avec une approche orientee resultats, gouvernance et durabilite.' }}</p>
                        @if (! empty($project->indicators))
                            <div class="mini-metrics">
                                @foreach (array_slice($project->indicators, 0, 2) as $metric)
                                    <strong>{{ $metric['value'] ?? '' }}<span>{{ $metric['label'] ?? '' }}</span></strong>
                                @endforeach
                            </div>
                        @endif
                        <small>{{ $project->starts_at?->format('d/m/Y') ?: 'Date a definir' }} - {{ $project->ends_at?->format('d/m/Y') ?: 'En cours' }}</small>
                        <a class="admin-link" href="{{ route('public.cooperation-projects.show', $project) }}">Voir le projet</a>
                    </article>
                @empty
                    <article class="empty-state">
                        <h2>Aucun projet actif publie pour le moment.</h2>
                        <p>Les projets de cooperation actifs apparaitront ici apres validation par l equipe JCA.</p>
                    </article>
                @endforelse
            </div>
            {{ $projects->links() }}
        </section>
    </main>
    @include('partials.footer')
</body>
</html>
