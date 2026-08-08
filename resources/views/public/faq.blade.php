<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('partials.head', [
        'title' => 'FAQ | JCA',
        'description' => 'Questions fréquentes JCA sur immigration, recrutement, espace client et accompagnement international.',
        'structuredData' => [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqs->flatten()->map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq->question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq->answer,
                ],
            ])->values()->all(),
        ],
    ])
</head>
<body>
    @include('partials.header')
    <main>
        <section class="page-hero page-hero-art is-immersive">
            <div>
                <span class="eyebrow">Questions fréquentes</span>
                <h1>FAQ</h1>
                <p>Les réponses essentielles avant une consultation ou le dépôt d’un dossier.</p>
                <div class="hero-actions">
                    <a class="button primary" href="{{ route('public.appointments') }}">Prendre rendez-vous</a>
                    <a class="button ghost" href="{{ $publicRoute('page.show', 'contact') }}">Nous contacter</a>
                </div>
            </div>
            <div class="page-hero-collage" aria-hidden="true">
                <img src="{{ asset('images/jca-immigration.webp') }}" alt="">
                <img src="{{ asset('images/jca-hero.webp') }}" alt="">
                <img src="{{ asset('images/jca-cooperation.webp') }}" alt="">
            </div>
        </section>

        <section class="page-guidance">
            <article><span>01</span><strong>Comprendre</strong><p>Verifier les bases avant de commencer.</p></article>
            <article><span>02</span><strong>Préparer</strong><p>Identifier les documents et informations utiles.</p></article>
            <article><span>03</span><strong>Avancer</strong><p>Demander une consultation ou contacter JCA.</p></article>
        </section>

        <section class="faq-preview">
            @forelse ($faqs as $category => $items)
                <div>
                    <span class="eyebrow">{{ $category }}</span>
                    <h2>{{ $category }}</h2>
                </div>
                @foreach ($items as $faq)
                    <details @if ($loop->first) open @endif>
                        <summary>{{ $faq->question }}</summary>
                        <p>{{ $faq->answer }}</p>
                    </details>
                @endforeach
            @empty
                <details open>
                    <summary>Aucune FAQ publiée</summary>
                    <p>Les réponses JCA seront publiées prochainement.</p>
                </details>
            @endforelse
        </section>
    </main>
    @include('partials.footer')
    @include('partials.cookie-banner')
</body>
</html>
