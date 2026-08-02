<header class="site-header" data-header>
    <a class="brand" href="{{ $publicRoute('home') }}" aria-label="Accueil JCA">
        <img class="brand-logo" src="{{ asset('images/logo_jca.jpg') }}" alt="Logo JCA">
        <span>
            <strong>{{ $siteSettings['brand_name'] ?? 'JCA' }}</strong>
            <small>{{ $siteSettings['brand_tagline'] ?? 'Immigration, recrutement et coopération' }}</small>
        </span>
    </a>

    <button class="nav-toggle" type="button" data-nav-toggle aria-label="Ouvrir le menu" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <nav class="main-nav" data-nav>
        <a href="{{ $publicRoute('home') }}">Accueil</a>
        <a href="{{ $publicRoute('page.show', 'qui-sommes-nous') }}">À propos</a>
        <a href="{{ $publicRoute('page.show', 'services') }}">Services</a>
        <a href="{{ $publicRoute('page.show', 'collaboration') }}">Collaboration</a>
        <a href="{{ $publicRoute('page.show', 'confidentialite') }}">Confidentialité</a>
        <a href="{{ $publicRoute('jobs.index') }}">Emplois</a>
        <a href="{{ $publicRoute('public.blog') }}">Blog</a>
        <a href="{{ $publicRoute('public.faq') }}">FAQ</a>
        <a href="{{ $publicRoute('page.show', 'contact') }}">Contact</a>
        <a class="nav-cta" href="{{ route('public.appointments') }}">Prendre rendez-vous</a>
        @auth
            <a class="nav-login" href="{{ route('portal.dashboard') }}">Mon espace</a>
        @else
            <a class="nav-login" href="{{ route('portal.login') }}">Connexion</a>
            <a href="{{ route('portal.register') }}">{{ __('site.actions.register') }}</a>
        @endauth
        <a class="translate-toggle" href="{{ route('locale.switch', app()->getLocale() === 'fr' ? 'en' : 'fr') }}">{{ strtoupper(app()->getLocale() === 'fr' ? 'en' : 'fr') }}</a>
    </nav>
</header>
