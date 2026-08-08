<x-admin.layout title="Paramètres">
    <section class="admin-panel admin-form-panel">
        <div class="admin-panel-head">
            <div>
                <h2>Paramètres du site</h2>
                <span>Identité, contact et pied de page</span>
            </div>
            <a class="button ghost" href="{{ route('home') }}">Voir le site</a>
        </div>
        @if (session('status'))
            <p class="form-note" data-state="success">{{ session('status') }}</p>
        @endif
        <form class="lead-form admin-form" method="post" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="form-grid">
                <label>Nom de marque<input name="brand_name" value="{{ old('brand_name', $settings['brand_name']) }}" required></label>
                <label>Slogan<input name="brand_tagline" value="{{ old('brand_tagline', $settings['brand_tagline']) }}"></label>
            </div>
            <label>Description pied de page<textarea name="footer_description" rows="5">{{ old('footer_description', $settings['footer_description']) }}</textarea></label>
            <div class="form-grid">
                <label>Email contact<input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" required></label>
                <label>Téléphone<input name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}"></label>
            </div>
            <div class="form-grid">
                <label>WhatsApp<input name="whatsapp" value="{{ old('whatsapp', $settings['whatsapp']) }}"></label>
                <label>Adresse<input name="address" value="{{ old('address', $settings['address']) }}"></label>
            </div>
            <label>Signature pied de page<input name="footer_signature" value="{{ old('footer_signature', $settings['footer_signature']) }}"></label>
            <div class="admin-upload-panel">
                <div>
                    <strong>Dossier de collaboration</strong>
                    <span>PDF, Word - 10 Mo max. Ce document sera visible et téléchargeable depuis la page Collaboration.</span>
                </div>
                @if (! empty($settings['collaboration_document_path']))
                    <a class="admin-link" href="{{ route('public.collaboration-document.download') }}" target="_blank" rel="noopener">
                        {{ $settings['collaboration_document_name'] ?: 'Dossier actuel' }}
                    </a>
                    <label class="check-inline">
                        <input type="checkbox" name="remove_collaboration_document" value="1">
                        Supprimer le dossier actuel
                    </label>
                @endif
                <label>Joindre un nouveau dossier<input type="file" name="collaboration_document" accept=".pdf,.doc,.docx"></label>
            </div>
            @if ($errors->any())
                <p class="form-note" data-state="error">{{ $errors->first() }}</p>
            @endif
            <button class="button primary" type="submit">Enregistrer les paramêtres</button>
        </form>
    </section>
</x-admin.layout>
