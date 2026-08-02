<form class="lead-form" method="post" action="{{ route('lead-requests.store') }}" enctype="multipart/form-data" data-lead-form>
    @csrf
    <input type="hidden" name="source" value="{{ $formSource }}">
    <input type="hidden" name="page_slug" value="{{ $pageSlug }}">
    <label class="honeypot" aria-hidden="true">Site web<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
    <label>Nom complet<input type="text" name="name" required></label>
    <label>Email<input type="email" name="email" required></label>
    <label>Téléphone / WhatsApp<input type="tel" name="phone"></label>
    <label>Motif
        <select name="topic">
            <option>Immigration</option>
            <option>Recrutement international</option>
            <option>Coopération internationale</option>
            <option>Partenariat</option>
            <option>Consultation stratégique</option>
        </select>
    </label>
    @if ($showAppointmentFields ?? false)
        <label>Date souhaitée<input type="date" name="preferred_date"></label>
        <label>Canal préféré
            <select name="preferred_channel">
                <option>Email</option>
                <option>WhatsApp</option>
                <option>Téléphone</option>
            </select>
        </label>
    @endif
    <label>Documents utiles
        <input type="file" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
    </label>
    <label>Message<textarea name="message" rows="5" required></textarea></label>
    <button class="button primary" type="submit" data-submit-label="{{ $submitLabel ?? 'Envoyer la demande' }}">{{ $submitLabel ?? 'Envoyer la demande' }}</button>
    <p class="form-note" role="status" aria-live="polite" data-form-note>
        @if (session('lead_success'))
            {{ session('lead_success') }}
        @endif
    </p>
</form>
