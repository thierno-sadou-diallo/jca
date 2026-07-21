<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('partials.head', [
        'title' => $article->title.' | JCA',
        'description' => $article->excerpt ?: str($article->body)->limit(160),
    ])
</head>
<body>
    @include('partials.header')
    <main>
        <section class="page-hero">
            <div>
                <span class="eyebrow">{{ \App\Models\Article::types()[$article->type] ?? 'Article' }}</span>
                <h1>{{ $article->title }}</h1>
                <p>{{ $article->excerpt }}</p>
            </div>
        </section>

        <section class="content-band article-body">
            <div class="admin-panel">
                <span class="eyebrow">{{ $article->published_at?->format('d/m/Y') ?: 'JCA' }}</span>
                <p>{!! nl2br(e($article->body)) !!}</p>
            </div>
        </section>
    </main>
    @include('partials.footer')
    @include('partials.cookie-banner')
</body>
</html>
