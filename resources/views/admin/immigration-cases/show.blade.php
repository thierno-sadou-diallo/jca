<x-admin.layout title="Dossier immigration">
    <section class="admin-detail-grid">
        <article class="admin-panel">
            <div class="admin-panel-head">
                <div>
                    <h2>{{ $case->reference }}</h2>
                    <span>{{ $case->user?->name ?? 'Client non lie' }} - {{ $statuses[$case->status] ?? $case->status }}</span>
                </div>
                @if ($case->user)
                    <a class="button ghost" href="{{ route('admin.clients.show', $case->user) }}">Client</a>
                @endif
            </div>

            <div class="admin-list">
                <div><strong>Programme</strong><span>{{ $case->program_type }}</span></div>
                <div><strong>Destination</strong><span>{{ $case->destination_country ?: 'Non indiqué' }}</span></div>
                <div><strong>Soumission</strong><span>{{ $case->submitted_at?->format('d/m/Y') ?: 'Non indiquée' }}</span></div>
                <div><strong>Décision</strong><span>{{ $case->decision_at?->format('d/m/Y') ?: 'Non indiquée' }}</span></div>
                <div><strong>Demande source</strong><span>{{ $case->leadRequest?->topic ?? 'Dossier direct' }}</span></div>
            </div>
        </article>

        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Mettre à jour</h2>
                <span>Suivi client</span>
            </div>
            @if (session('status'))
                <p class="form-note" data-state="success">{{ session('status') }}</p>
            @endif
            <form class="lead-form admin-form" method="post" action="{{ route('admin.immigration-cases.update', $case) }}">
                @csrf
                @method('PATCH')
                <label>Programme<input name="program_type" value="{{ old('program_type', $case->program_type) }}" required></label>
                <div class="form-grid">
                    <label>Pays de destination<input name="destination_country" value="{{ old('destination_country', $case->destination_country) }}"></label>
                    <label>Statut
                        <select name="status" required>
                            @foreach ($statuses as $value => $label)
                                <option value="{{ $value }}" @selected($case->status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="form-grid">
                    <label>Date de soumission<input type="date" name="submitted_at" value="{{ old('submitted_at', $case->submitted_at?->toDateString()) }}"></label>
                    <label>Date de décision<input type="date" name="decision_at" value="{{ old('decision_at', $case->decision_at?->toDateString()) }}"></label>
                </div>
                <label>Note de suivi<textarea name="note" rows="5" placeholder="Expliquez la mise à jour visible dans l’historique.">{{ old('note') }}</textarea></label>
                @if ($errors->any())
                    <p class="form-note" data-state="error">{{ $errors->first() }}</p>
                @endif
                <button class="button primary" type="submit">Enregistrer</button>
            </form>
        </article>
    </section>

    <section class="admin-panel">
        <div class="admin-panel-head">
            <h2>Historique du dossier</h2>
            <span>{{ $case->histories->count() }} evenement(s)</span>
        </div>
        <div class="case-timeline">
            @forelse ($case->histories->sortByDesc('created_at') as $history)
                <article>
                    <span>{{ $history->created_at->format('d/m/Y H:i') }} - {{ $history->user?->name ?? 'Systeme' }}</span>
                    <strong>{{ $statuses[$history->status] ?? $history->status }}</strong>
                    <p>{{ $history->note ?: 'Mise à jour du statut.' }}</p>
                </article>
            @empty
                <article>
                    <span>Historique</span>
                    <strong>Aucun evenement</strong>
                    <p>Les changements de statut apparaîtront ici.</p>
                </article>
            @endforelse
        </div>
    </section>
</x-admin.layout>
