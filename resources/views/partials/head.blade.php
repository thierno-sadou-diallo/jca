@php
    $pageTitle = $title ?? (($page['title'] ?? 'JCA').' | JCA');
    $pageDescription = $description ?? ($page['intro'] ?? 'JCA accompagne les projets d immigration, de recrutement international, de cooperation et de mobilite.');
    $currentRoute = Route::currentRouteName();
    $currentSlug = $slug ?? request()->route('slug');

    $localizedUrl = function (string $locale) use ($currentRoute, $currentSlug) {
        $path = trim(request()->path(), '/');
        $path = preg_replace('#^(fr|en)/#', '', $path);

        if ($currentRoute === 'home' || request()->is('/') || in_array(request()->path(), ['fr', 'en'], true)) {
            return url($locale);
        }

        if (($currentRoute === 'page.show' || $currentRoute === 'localized.page.show') && is_string($currentSlug)) {
            return url($locale.'/'.$currentSlug);
        }

        if ($path !== '' && ! str_starts_with($path, 'admin') && ! str_starts_with($path, 'espace')) {
            return url($locale.'/'.$path);
        }

        return url()->current();
    };
@endphp
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="{{ $pageDescription }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:type" content="{{ $ogType ?? 'website' }}">
<meta property="og:image" content="{{ $ogImage ?? asset('images/logo_off.jpg') }}">
<link rel="canonical" href="{{ url()->current() }}">
<link rel="alternate" hreflang="fr" href="{{ $localizedUrl('fr') }}">
<link rel="alternate" hreflang="en" href="{{ $localizedUrl('en') }}">
<link rel="alternate" hreflang="x-default" href="{{ $localizedUrl('fr') }}">
<title>{{ $pageTitle }}</title>
@isset($structuredData)
    <script nonce="{{ request()->attributes->get('csp_nonce') }}" type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endisset
@vite(['resources/css/app.css', 'resources/js/app.js'])
