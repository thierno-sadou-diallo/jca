<form class="lead-form" method="post" action="{{ route('lead-requests.store') }}" enctype="multipart/form-data" data-lead-form>
    @csrf
    <input type="hidden" name="source" value="{{ $formSource }}">
    <input type="hidden" name="page_slug" value="{{ $pageSlug }}">
    <label class="honeypot" aria-hidden="true">Site web<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
    <label>{{ __('Nom complet') }}<input type="text" name="name" required></label>
    <label>{{ __('Email') }}<input type="email" name="email" required></label>
    <label>{{ __('Téléphone / WhatsApp') }}<input type="tel" name="phone"></label>
    <label>{{ __('Motif') }}
        <select name="topic">
            <option value="Immigration">{{ __('Immigration') }}</option>
            <option value="Recrutement international">{{ __('Recrutement international') }}</option>
            <option value="Coopération internationale">{{ __('Coopération internationale') }}</option>
            <option value="Partenariat">{{ __('Partenariat') }}</option>
            <option value="Consultation stratégique">{{ __('Consultation stratégique') }}</option>
        </select>
    </label>
    @if ($showAppointmentFields ?? false)
        <label>{{ __('Date souhaitée') }}<input type="date" name="preferred_date"></label>
        <label>{{ __('Canal préféré') }}
            <select name="preferred_channel">
                <option value="Email">{{ __('Email') }}</option>
                <option value="WhatsApp">{{ __('WhatsApp') }}</option>
                <option value="Téléphone">{{ app()->getLocale() === 'en' ? 'Phone' : 'Téléphone' }}</option>
            </select>
        </label>
    @endif
    <label>{{ __('Documents utiles') }}
        <input type="file" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
    </label>
    <label>{{ __('Message') }}<textarea name="message" rows="5" required></textarea></label>
    <button class="button primary" type="submit" data-submit-label="{{ __($submitLabel ?? 'Envoyer la demande') }}">{{ __($submitLabel ?? 'Envoyer la demande') }}</button>
    <p class="form-note" role="status" aria-live="polite" data-form-note>
        @if (session('lead_success'))
            {{ session('lead_success') }}
        @endif
    </p>
</form>
