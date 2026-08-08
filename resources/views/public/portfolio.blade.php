<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('partials.head', [
        'title' => __('Portfolio').' | JCA',
        'description' => __('Événements, forums, relations presse et temps forts de JCA.'),
    ])
</head>
<body>
    @include('partials.header')

    <main>
        <section class="page-hero portfolio-hero">
            <span class="eyebrow">{{ __('Portfolio') }}</span>
            <h1>{{ __('Événements, forums et relations presse.') }}</h1>
            <p>{{ __('Un espace vivant pour suivre les rencontres, prises de parole, collaborations et moments institutionnels de JCA.') }}</p>
        </section>

        <section class="portfolio-showcase">
            <div class="portfolio-grid">
                @forelse ($items as $item)
                    <article class="portfolio-card reveal">
                        @if ($item->image_path)
                            <img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->title }}" loading="lazy">
                        @endif
                        <div>
                            <span>{{ __(ucfirst(str_replace('-', ' ', $item->type))) }}</span>
                            <h2>{{ $item->title }}</h2>
                            <p>{{ $item->excerpt }}</p>
                            <small>{{ $item->event_date ? $item->event_date->format('d/m/Y') : '' }}{{ $item->location ? ' - '.$item->location : '' }}</small>
                        </div>
                    </article>
                @empty
                    <article class="portfolio-empty">
                        <h2>{{ __('Portfolio en préparation.') }}</h2>
                        <p>{{ __('Les prochains événements, forums et relations presse seront publiés ici.') }}</p>
                    </article>
                @endforelse
            </div>

            {{ $items->links() }}
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
