<footer class="footer">
    <div class="footer-grid">
        <div>
            <a class="brand footer-brand" href="{{ $publicRoute('home') }}">
                <img class="brand-logo" src="{{ asset('images/logo_jca.jpg') }}" alt="Logo JCA">
                <span>
                    <strong>{{ $siteSettings['brand_name'] ?? 'JCA' }}</strong>
                    <small>{{ $siteSettings['brand_tagline'] ?? 'Immigration, recrutement et cooperation' }}</small>
                </span>
            </a>
            <p>{{ $siteSettings['footer_description'] ?? 'Cabinet international de conseil en immigration, mobilite, recrutement et cooperation.' }}</p>
        </div>
        <div>
            <h3>Services</h3>
            <a href="{{ $publicRoute('page.show', 'immigration') }}">Immigration</a>
            <a href="{{ $publicRoute('page.show', 'recrutement-international') }}">Recrutement international</a>
            <a href="{{ $publicRoute('page.show', 'cooperation-internationale') }}">Coopération internationale</a>
            <a href="{{ $publicRoute('page.show', 'consultation') }}">Service-conseils strategique</a>
        </div>
        <div>
            <h3>Informations</h3>
            <a href="{{ $publicRoute('page.show', 'equipe') }}">Équipe</a>
            <a href="{{ $publicRoute('page.show', 'collaboration') }}">Collaboration</a>
            <a href="{{ $publicRoute('page.show', 'confidentialite') }}">Confidentialité</a>
            <a href="{{ $publicRoute('public.faq') }}">FAQ</a>
            <a href="{{ $publicRoute('legal.show', 'mentions-legales') }}">Mentions légales</a>
            <a href="{{ $publicRoute('legal.show', 'politique-confidentialite') }}">Politique de confidentialité</a>
        </div>
        <div>
            <h3>Contact</h3>
            <a href="{{ $publicRoute('page.show', 'contact') }}">Formulaire</a>
            <a href="mailto:{{ $siteSettings['contact_email'] ?? 'contact@jcaconseil.com' }}">{{ $siteSettings['contact_email'] ?? 'contact@jcaconseil.com' }}</a>
            <a href="tel:{{ preg_replace('/\D+/', '', $siteSettings['contact_phone'] ?? '+221789685116') }}">{{ $siteSettings['contact_phone'] ?? '78 968 51 16' }}</a>
            @if (! empty($siteSettings['whatsapp']))
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $siteSettings['whatsapp']) }}">WhatsApp</a>
            @endif
            <a href="{{ route('public.appointments') }}">Prendre rendez-vous</a>
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
