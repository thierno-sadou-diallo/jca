<x-admin.layout title="Nouveau dossier">
    <section class="admin-detail-grid">
        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Créer un dossier immigration</h2>
                <span>Conversion ou ouverture directe</span>
            </div>
            <form class="lead-form admin-form" method="post" action="{{ route('admin.immigration-cases.store') }}">
                @csrf
                <label>Client
                    <select name="user_id" required>
                        @foreach ($clients as $availableClient)
                            <option value="{{ $availableClient->id }}" @selected(optional($client)->id === $availableClient->id)>{{ $availableClient->name }} - {{ $availableClient->email }}</option>
                        @endforeach
                    </select>
                </label>
                @if ($lead)
                    <input type="hidden" name="lead_request_id" value="{{ $lead->id }}">
                    <p class="form-note">Demande liée : {{ $lead->topic }} - {{ $lead->email }}</p>
                @endif
                <label>Programme<input name="program_type" value="{{ old('program_type', $lead?->topic ?: 'Résidence permanente') }}" required></label>
                <div class="form-grid">
                    <label>Pays de destination<input name="destination_country" value="{{ old('destination_country') }}"></label>
                    <label>Date de soumission<input type="date" name="submitted_at" value="{{ old('submitted_at', now()->toDateString()) }}"></label>
                </div>
                <label>Statut
                    <select name="status" required>
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}" @selected($value === \App\Models\ImmigrationCase::STATUS_RECEIVED)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Note initiale<textarea name="note" rows="5" placeholder="Résumé de la situation, pièces attendues, prochaine étape...">{{ old('note') }}</textarea></label>
                @if ($errors->any())
                    <p class="form-note" data-state="error">{{ $errors->first() }}</p>
                @endif
                <button class="button primary" type="submit">Créer le dossier</button>
            </form>
        </article>

        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Repere</h2>
                <span>Statuts standards</span>
            </div>
            <div class="admin-list">
                @foreach ($statuses as $label)
                    <div><strong>{{ $label }}</strong><span>Étape visible par le client dans son espace.</span></div>
                @endforeach
            </div>
        </article>
    </section>
</x-admin.layout>
