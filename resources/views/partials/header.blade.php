<header class="site-header" data-header>
    <a class="brand" href="{{ route('home') }}" aria-label="Accueil JCA">
        <img class="brand-logo" src="{{ asset('images/logo_jca.jpg') }}" alt="Logo JCA">
        <span>
            <strong>{{ $siteSettings['brand_name'] ?? 'JCA' }}</strong>
            <small>{{ $siteSettings['brand_tagline'] ?? 'Immigration, recruitment & cooperation' }}</small>
        </span>
    </a>

    <button class="nav-toggle" type="button" data-nav-toggle aria-label="Ouvrir le menu" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <nav class="main-nav" data-nav>
        <a href="{{ route('home') }}">Accueil</a>
        <a href="{{ route('page.show', 'qui-sommes-nous') }}">A propos</a>
        <a href="{{ route('page.show', 'services') }}">Services</a>
        <a href="{{ route('public.cooperation-projects') }}">Cooperation</a>
        <a href="{{ route('public.humanitarian-programs') }}">Humanitaire</a>
        @auth
            <a class="nav-login" href="{{ route('portal.dashboard') }}">Mon espace</a>
        @else
            <a class="nav-login" href="{{ route('portal.login') }}">Connexion</a>
            <a href="{{ route('portal.register') }}">{{ __('site.actions.register') }}</a>
        @endauth
        <a class="translate-toggle" href="{{ route('locale.switch', app()->getLocale() === 'fr' ? 'en' : 'fr') }}">{{ strtoupper(app()->getLocale() === 'fr' ? 'en' : 'fr') }}</a>
    </nav>
</header>
