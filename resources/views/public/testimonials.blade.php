<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('partials.head', [
        'title' => __('Témoignages').' | JCA',
        'description' => __('Avis publiés par les visiteurs JCA.'),
    ])
</head>
<body>
    @include('partials.header')
    <main>
        <section class="page-hero page-hero-art is-immersive">
            <div>
                <span class="eyebrow">{{ __('Témoignages') }}</span>
                <h1>{{ __('Avis visiteurs') }}</h1>
                <p>{{ __('Retours publiés directement par les personnes, employeurs et partenaires qui souhaitent partager leur expérience avec JCA.') }}</p>
                <div class="hero-actions">
                    <a class="button primary" href="{{ $publicRoute('home') }}#temoignages">{{ __('Publier un témoignage') }}</a>
                    <a class="button ghost" href="{{ $publicRoute('page.show', 'services') }}">{{ __('Voir les services') }}</a>
                </div>
            </div>
            <div class="page-hero-collage" aria-hidden="true">
                <img src="{{ asset('images/jca-recruitment.webp') }}" alt="">
                <img src="{{ asset('images/jca-hero.webp') }}" alt="">
                <img src="{{ asset('images/jca-cooperation.webp') }}" alt="">
            </div>
        </section>

        <section class="testimonial-index-section">
            <div class="testimonial-index-grid">
                @forelse ($testimonials as $testimonial)
                    <figure class="service-testimonial-card reveal">
                        <blockquote>“{{ $testimonial->quote }}”</blockquote>
                        <figcaption>
                            <strong>{{ $testimonial->author_name }}</strong>
                            <span>{{ $testimonial->author_role ?? __('Client JCA') }}{{ filled($testimonial->organization ?? null) ? ' - '.$testimonial->organization : '' }}</span>
                        </figcaption>
                    </figure>
                @empty
                    <article class="empty-state">
                        <h2>{{ __('Aucun témoignage publié pour le moment.') }}</h2>
                        <p>{{ __('Les témoignages des visiteurs apparaîtront ici après leur publication.') }}</p>
                    </article>
                @endforelse
            </div>

            @if (method_exists($testimonials, 'links'))
                {{ $testimonials->links() }}
            @endif
        </section>
    </main>
    @include('partials.footer')
    @include('partials.cookie-banner')
</body>
</html>
