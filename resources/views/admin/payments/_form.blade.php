@csrf
<div class="form-grid">
    <label>Client
        <select name="user_id" required>
            <option value="">Selectionner</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" @selected((int) old('user_id', $payment->user_id) === $client->id)>{{ $client->name }} - {{ $client->email }}</option>
            @endforeach
        </select>
    </label>
    <label>Rendez-vous lie
        <select name="appointment_id">
            <option value="">Aucun</option>
            @foreach ($appointments as $appointment)
                <option value="{{ $appointment->id }}" @selected((int) old('appointment_id', $payment->appointment_id) === $appointment->id)>{{ $appointment->topic }} - {{ $appointment->starts_at?->format('d/m/Y H:i') ?: 'Date à confirmer' }}</option>
            @endforeach
        </select>
    </label>
</div>
<div class="form-grid">
    <label>Reference<input name="reference" value="{{ old('reference', $payment->reference) }}" placeholder="Reference automatique si vide"></label>
    <label>Statut
        <select name="status" required>
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $payment->status ?: 'pending') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>
</div>
<div class="form-grid">
    <label>Montant<input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount', $payment->amount) }}" required></label>
    <label>Devise<input name="currency" value="{{ old('currency', $payment->currency ?: 'CAD') }}" maxlength="3" required></label>
</div>
<div class="form-grid">
    <label>Prestataire<input name="provider" value="{{ old('provider', $payment->provider) }}" placeholder="Stripe, virement, Interac..."></label>
    <label>Lien de paiement<input type="url" name="payment_url" value="{{ old('payment_url', $payment->payload['payment_url'] ?? '') }}" placeholder="https://..."></label>
</div>
<label>Note client / interne<textarea name="note" rows="5">{{ old('note', $payment->payload['note'] ?? '') }}</textarea></label>
@if ($errors->any())
    <p class="form-note" data-state="error">{{ $errors->first() }}</p>
@endif
<button class="button primary" type="submit">{{ $submitLabel }}</button>
