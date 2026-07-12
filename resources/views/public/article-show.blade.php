<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $article->excerpt }}">
    <title>{{ $article->title }} | JCA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
</body>
</html>
