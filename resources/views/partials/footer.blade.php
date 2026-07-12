<footer class="footer">
    <div class="footer-grid">
        <div>
            <a class="brand footer-brand" href="{{ route('home') }}">
                <img class="brand-logo" src="{{ asset('images/logo_jca.jpg') }}" alt="Logo JCA">
                <span>
                    <strong>{{ $siteSettings['brand_name'] ?? 'JCA' }}</strong>
                    <small>{{ $siteSettings['brand_tagline'] ?? 'Immigration et developpement international' }}</small>
                </span>
            </a>
            <p>{{ $siteSettings['footer_description'] ?? 'Cabinet international de conseil et d accompagnement specialise en immigration, mobilite internationale, recrutement international, cooperation internationale et developpement durable.' }}</p>
        </div>
        <div>
            <h3>Domaines</h3>
            <a href="{{ route('page.show', 'immigration') }}">Immigration</a>
            <a href="{{ route('page.show', 'recrutement-international') }}">Recrutement international</a>
            <a href="{{ route('page.show', 'cooperation-internationale') }}">Cooperation internationale</a>
            <a href="{{ route('public.cooperation-projects') }}">Projets de cooperation</a>
            <a href="{{ route('public.humanitarian-programs') }}">Programmes humanitaires</a>
            <a href="{{ route('page.show', 'developpement-durable') }}">Developpement durable</a>
        </div>
        <div>
            <h3>Ressources</h3>
            <a href="{{ route('public.news') }}">Actualites</a>
            <a href="{{ route('public.blog') }}">Blog</a>
            <a href="{{ route('public.faq') }}">FAQ</a>
            <a href="{{ route('public.partners') }}">Partenaires</a>
        </div>
        <div>
            <h3>Contact</h3>
            <a href="{{ route('page.show', 'contact') }}">Formulaire</a>
            <a href="mailto:{{ $siteSettings['contact_email'] ?? 'contact@jca-international.com' }}">{{ $siteSettings['contact_email'] ?? 'contact@jca-international.com' }}</a>
            @if (! empty($siteSettings['contact_phone']))
                <a href="tel:{{ $siteSettings['contact_phone'] }}">{{ $siteSettings['contact_phone'] }}</a>
            @endif
            @if (! empty($siteSettings['whatsapp']))
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $siteSettings['whatsapp']) }}">WhatsApp</a>
            @endif
            <a href="{{ route('page.show', 'consultation') }}">Prendre rendez-vous</a>
            <a href="{{ route('portal.register') }}">Creer un espace client</a>
            <div class="language-switcher" aria-label="Langues">
                <a href="{{ route('locale.switch', 'fr') }}">FR</a>
                <a href="{{ route('locale.switch', 'en') }}">EN</a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; {{ date('Y') }} {{ $siteSettings['brand_name'] ?? 'JCA' }}. Tous droits reserves.</span>
        <span>{{ $siteSettings['footer_signature'] ?? 'Des ponts entre les talents, les organisations et les opportunites.' }}</span>
    </div>
</footer>
