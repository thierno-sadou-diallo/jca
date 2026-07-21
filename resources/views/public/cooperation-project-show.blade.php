<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', [
        'title' => $project->title.' | JCA',
        'description' => str($project->description ?: 'Projet de cooperation internationale accompagne par JCA.')->limit(155),
    ])
</head>
<body>
    @include('partials.header')
    <main>
        <section class="page-hero">
            <div>
                <span class="eyebrow">{{ $project->sector ?: 'Cooperation internationale' }}</span>
                <h1>{{ $project->title }}</h1>
                <p>{{ $project->country ?: 'Projet international' }} - {{ $project->starts_at?->format('d/m/Y') ?: 'Date a definir' }} - {{ $project->ends_at?->format('d/m/Y') ?: 'En cours' }}</p>
            </div>
        </section>

        <section class="content-band article-body">
            <div class="admin-panel">
                <img class="article-cover" src="{{ $project->image_path ? asset('storage/'.$project->image_path) : asset('images/jca-cooperation.webp') }}" alt="{{ $project->title }}" loading="lazy">
                <span class="eyebrow">Projet actif</span>
                <p>{!! nl2br(e($project->description ?: 'Ce projet de cooperation est accompagne par JCA avec une approche structuree, orientee impact, coordination et resultats durables.')) !!}</p>
                @if (! empty($project->indicators))
                    <div class="impact-metrics">
                        @foreach ($project->indicators as $metric)
                            <article><strong>{{ $metric['value'] ?? '' }}</strong><span>{{ $metric['label'] ?? '' }}</span></article>
                        @endforeach
                    </div>
                @endif
                <a class="button primary" href="{{ route('page.show', 'contact') }}">Demander une collaboration</a>
            </div>
        </section>
    </main>
    @include('partials.footer')
    @include('partials.cookie-banner')
</body>
</html>
