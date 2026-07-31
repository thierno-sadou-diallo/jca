<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', [
        'title' => $program->title.' | JCA',
        'description' => str($program->description ?: 'Programme humanitaire accompagne par JCA.')->limit(155),
    ])
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
                <img class="article-cover" src="{{ $program->image_path ? asset('storage/'.$program->image_path) : asset('images/jca-hero.webp') }}" alt="{{ $program->title }}" loading="lazy">
                <span class="eyebrow">Programme actif</span>
                <p>{!! nl2br(e($program->description ?: 'Ce programme humanitaire est accompagne par JCA pour soutenir les publics, renforcer les capacités et créer un impact social durable.')) !!}</p>
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
    @include('partials.cookie-banner')
</body>
</html>
