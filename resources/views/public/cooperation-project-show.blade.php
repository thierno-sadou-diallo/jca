<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $project->title }} - JCA</title>
    <meta name="description" content="{{ str($project->description ?: 'Projet de cooperation internationale accompagne par JCA.')->limit(155) }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                <img class="article-cover" src="{{ $project->image_path ? asset('storage/'.$project->image_path) : asset('images/jca-cooperation.png') }}" alt="{{ $project->title }}">
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
</body>
</html>
