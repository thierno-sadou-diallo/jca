<footer class="footer">
    <div class="footer-grid">
        <div>
            <a class="brand footer-brand" href="{{ route('home') }}">
                <img class="brand-logo" src="{{ asset('images/logo_jca.jpg') }}" alt="Logo JCA">
                <span>
                    <strong>{{ $siteSettings['brand_name'] ?? 'JCA' }}</strong>
                    <small>{{ $siteSettings['brand_tagline'] ?? 'Immigration et développement international' }}</small>
                </span>
            </a>
            <p>{{ $siteSettings['footer_description'] ?? 'Cabinet international de conseil et d’accompagnement spécialisé en immigration, mobilité internationale, recrutement international, coopération internationale et développement durable.' }}</p>
        </div>
        <div>
            <h3>Domaines</h3>
            <a href="{{ route('page.show', 'immigration') }}">Immigration</a>
            <a href="{{ route('page.show', 'recrutement-international') }}">Recrutement international</a>
            <a href="{{ route('page.show', 'cooperation-internationale') }}">Coopération internationale</a>
            <a href="{{ route('public.cooperation-projects') }}">Projets de coopération</a>
            <a href="{{ route('public.humanitarian-programs') }}">Programmes humanitaires</a>
            <a href="{{ route('page.show', 'developpement-durable') }}">Développement durable</a>
        </div>
        <div>
            <h3>Ressources</h3>
            <a href="{{ route('public.news') }}">Actualités</a>
            <a href="{{ route('public.blog') }}">Blog</a>
            <a href="{{ route('public.faq') }}">FAQ</a>
            <a href="{{ route('public.partners') }}">Partenaires</a>
            <a href="{{ route('page.show', 'accreditations') }}">Accréditations</a>
            <a href="{{ route('legal.show', 'mentions-legales') }}">Mentions légales</a>
            <a href="{{ route('legal.show', 'politique-confidentialite') }}">Politique de confidentialité</a>
            <a href="{{ route('legal.show', 'conditions-utilisation') }}">Conditions d’utilisation</a>
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
            <a href="{{ route('public.appointments') }}">Prendre rendez-vous</a>
            <a href="{{ route('portal.register') }}">Créer un espace client</a>
            <div class="language-switcher" aria-label="Langues">
                <a href="{{ route('locale.switch', 'fr') }}">FR</a>
                <a href="{{ route('locale.switch', 'en') }}">EN</a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; {{ date('Y') }} {{ $siteSettings['brand_name'] ?? 'JCA' }}. Tous droits réservés.</span>
        <span>{{ $siteSettings['footer_signature'] ?? 'Des ponts entre les talents, les organisations et les opportunités.' }}</span>
    </div>
</footer>
