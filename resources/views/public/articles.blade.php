<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('partials.head', [
        'title' => $title.' | JCA',
        'description' => $intro,
    ])
</head>
<body>
    @include('partials.header')
    <main>
        <section class="page-hero page-hero-art is-immersive">
            <div>
                <span class="eyebrow">{{ $eyebrow }}</span>
                <h1>{{ $title }}</h1>
                <p>{{ $intro }}</p>
                <div class="hero-actions">
                    <a class="button primary" href="{{ route('public.appointments') }}">Prendre rendez-vous</a>
                    <a class="button ghost" href="{{ $publicRoute('page.show', 'services') }}">Voir les services</a>
                </div>
            </div>
            <div class="page-hero-collage" aria-hidden="true">
                <img src="{{ asset('images/jca-recruitment.webp') }}" alt="">
                <img src="{{ asset('images/jca-immigration.webp') }}" alt="">
                <img src="{{ asset('images/jca-cooperation.webp') }}" alt="">
            </div>
        </section>

        <section class="page-guidance">
            <article><span>01</span><strong>S’informer</strong><p>Lire les analyses et conseils publiés par JCA.</p></article>
            <article><span>02</span><strong>Comparer</strong><p>Comprendre les options avant de choisir une démarche.</p></article>
            <article><span>03</span><strong>Agir</strong><p>Passer de l’information à un dossier suivi.</p></article>
        </section>

        <section class="content-band">
            <div class="cards-grid">
                @forelse ($articles as $article)
                    <article class="news-card reveal">
                        <span>{{ $article->published_at?->format('d/m/Y') ?: 'JCA' }}</span>
                        <h3>{{ $article->title }}</h3>
                        <p>{{ $article->excerpt ?: str($article->body)->limit(150) }}</p>
                        <a class="admin-link" href="{{ $publicRoute('public.articles.show', $article) }}">Lire</a>
                    </article>
                @empty
                    <article class="empty-state">
                        <h2>Aucun contenu publie pour le moment.</h2>
                        <p>Les publications JCA apparaîtront ici des leur publication.</p>
                    </article>
                @endforelse
            </div>
            {{ $articles->links() }}
        </section>
    </main>
    @include('partials.footer')
    @include('partials.cookie-banner')
</body>
</html>
