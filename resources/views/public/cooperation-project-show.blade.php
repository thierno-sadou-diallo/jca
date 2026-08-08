<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', [
        'title' => $project->title.' | JCA',
        'description' => str($project->description ?: 'Projet de coopération internationale accompagné par JCA.')->limit(155),
    ])
</head>
<body>
    @include('partials.header')
    <main>
        <section class="page-hero">
            <div>
                <span class="eyebrow">{{ $project->sector ?: 'Coopération internationale' }}</span>
                <h1>{{ $project->title }}</h1>
                <p>{{ $project->country ?: 'Projet international' }} - {{ $project->starts_at?->format('d/m/Y') ?: 'Date à définir' }} - {{ $project->ends_at?->format('d/m/Y') ?: 'En cours' }}</p>
            </div>
        </section>

        <section class="content-band article-body">
            <div class="admin-panel">
                <img class="article-cover" src="{{ $project->image_path ? asset('storage/'.$project->image_path) : asset('images/jca-cooperation.webp') }}" alt="{{ $project->title }}" loading="lazy">
                <span class="eyebrow">Projet actif</span>
                <p>{!! nl2br(e($project->description ?: 'Ce projet de coopération est accompagné par JCA avec une approche structurée, orientée impact, coordination et résultats durables.')) !!}</p>
                @if (! empty($project->indicators))
                    <div class="impact-metrics">
                        @foreach ($project->indicators as $metric)
                            <article><strong>{{ $metric['value'] ?? '' }}</strong><span>{{ $metric['label'] ?? '' }}</span></article>
                        @endforeach
                    </div>
                @endif
                <a class="button primary" href="{{ $publicRoute('page.show', 'contact') }}">Demander une collaboration</a>
            </div>
        </section>
    </main>
    @include('partials.footer')
    @include('partials.cookie-banner')
</body>
</html>
