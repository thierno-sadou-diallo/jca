<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $program->title }} - JCA</title>
    <meta name="description" content="{{ str($program->description ?: 'Programme humanitaire accompagne par JCA.')->limit(155) }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('partials.header')
    <main>
        <section class="page-hero">
            <div>
                <span class="eyebrow">{{ $program->focus_area ?: 'Action humanitaire' }}</span>
                <h1>{{ $program->title }}</h1>
                <p>{{ $program->country ?: 'Programme international' }} - inclusion, accompagnement et impact humain.</p>
            </div>
        </section>

        <section class="content-band article-body">
            <div class="admin-panel">
                <img class="article-cover" src="{{ $program->image_path ? asset('storage/'.$program->image_path) : asset('images/jca-hero.png') }}" alt="{{ $program->title }}">
                <span class="eyebrow">Programme actif</span>
                <p>{!! nl2br(e($program->description ?: 'Ce programme humanitaire est accompagne par JCA pour soutenir les publics, renforcer les capacites et creer un impact social durable.')) !!}</p>
                @if (! empty($program->impact_metrics))
                    <div class="impact-metrics">
                        @foreach ($program->impact_metrics as $metric)
                            <article><strong>{{ $metric['value'] ?? '' }}</strong><span>{{ $metric['label'] ?? '' }}</span></article>
                        @endforeach
                    </div>
                @endif
                <a class="button primary" href="{{ route('page.show', 'contact') }}">Proposer un partenariat</a>
            </div>
        </section>
    </main>
    @include('partials.footer')
</body>
</html>
