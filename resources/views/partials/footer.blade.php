<footer class="footer">
    <div class="footer-grid">
        <div>
            <a class="brand footer-brand" href="{{ $publicRoute('home') }}">
                <img class="brand-logo" src="{{ asset('images/logo_off.webp') }}" alt="Logo JCA">
                <span>
                    <strong>{{ $siteSettings['brand_name'] ?? 'JCA' }}</strong>
                    <small>{{ $siteSettings['brand_tagline'] ?? __('Immigration, recrutement et coopération') }}</small>
                </span>
            </a>
            <p>{{ $siteSettings['footer_description'] ?? __('Cabinet international de conseil en immigration, mobilité, recrutement et coopération.') }}</p>
        </div>
        <div>
            <h3>{{ __('Services') }}</h3>
            <a href="{{ $publicRoute('page.show', 'immigration') }}">{{ __('Immigration') }}</a>
            <a href="{{ $publicRoute('page.show', 'recrutement-international') }}">{{ __('Recrutement international') }}</a>
            <a href="{{ $publicRoute('page.show', 'cooperation-internationale') }}">{{ __('Coopération internationale') }}</a>
            <a href="{{ $publicRoute('page.show', 'consultation') }}">{{ __('Service-conseil stratégique') }}</a>
        </div>
        <div>
            <h3>{{ __('Informations') }}</h3>
            <a href="{{ $publicRoute('page.show', 'qui-sommes-nous') }}">{{ __('À propos') }}</a>
            <a href="{{ $publicRoute('page.show', 'collaboration') }}">{{ __('Collaboration') }}</a>
            <a href="{{ $publicRoute('page.show', 'confidentialite') }}">{{ __('Confidentialité') }}</a>
            <a href="{{ app()->getLocale() === 'en' ? route('localized.public.portfolio', 'en') : route('public.portfolio') }}">Portfolio</a>
            <a href="{{ $publicRoute('public.blog') }}">{{ __('Blog') }}</a>
            <a href="{{ $publicRoute('public.faq') }}">FAQ</a>
            <a href="{{ $publicRoute('legal.show', 'mentions-legales') }}">{{ __('Mentions légales') }}</a>
            <a href="{{ $publicRoute('legal.show', 'politique-confidentialite') }}">{{ __('Politique de confidentialité') }}</a>
        </div>
        <div>
            <h3>{{ __('Contact') }}</h3>
            <a href="{{ $publicRoute('page.show', 'contact') }}">{{ __('Formulaire') }}</a>
            <a href="mailto:{{ $siteSettings['contact_email'] ?? 'contact@jcaconseil.com' }}">{{ $siteSettings['contact_email'] ?? 'contact@jcaconseil.com' }}</a>
            <a href="tel:{{ preg_replace('/\D+/', '', $siteSettings['contact_phone'] ?? '+221789685116') }}">{{ $siteSettings['contact_phone'] ?? '78 968 51 16' }}</a>
            @if (! empty($siteSettings['whatsapp']))
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $siteSettings['whatsapp']) }}">WhatsApp</a>
            @endif
            <a href="{{ route('public.appointments') }}">{{ __('Prendre rendez-vous') }}</a>
            <div class="language-switcher" aria-label="{{ __('Langues') }}">
                <a href="{{ route('locale.switch', 'fr') }}">FR</a>
                <a href="{{ route('locale.switch', 'en') }}">EN</a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; {{ date('Y') }} {{ $siteSettings['brand_name'] ?? 'JCA' }}. {{ __('Tous droits réservés.') }}</span>
        <span>{{ $siteSettings['footer_signature'] ?? __('Des ponts entre les talents, les organisations et les opportunités.') }}</span>
    </div>
</footer>
