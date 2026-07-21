<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('partials.head', [
        'title' => $legalPage['title'].' | JCA',
        'description' => $legalPage['description'],
    ])
</head>
<body>
    @include('partials.header')
    <main>
        <section class="page-hero">
            <div>
                <span class="eyebrow">Conformite JCA</span>
                <h1>{{ $legalPage['title'] }}</h1>
                <p>{{ $legalPage['description'] }}</p>
            </div>
        </section>

        <section class="content-band legal-content">
            @foreach ($legalPage['sections'] as $section)
                <article class="admin-panel">
                    <h2>{{ $section['title'] }}</h2>
                    @foreach ($section['paragraphs'] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                    @isset($section['items'])
                        <ul class="check-list">
                            @foreach ($section['items'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    @endisset
                </article>
            @endforeach
        </section>
    </main>
    @include('partials.footer')
    @include('partials.cookie-banner')
</body>
</html>
