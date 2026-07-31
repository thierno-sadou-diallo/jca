<x-admin.layout title="Traiter un rendez-vous">
    <section class="admin-detail-grid">
        <article class="admin-panel">
            <div class="admin-panel-head">
                <div>
                    <h2>{{ $appointment->topic }}</h2>
                    <span>{{ $statuses[$appointment->status] ?? $appointment->status }}</span>
                </div>
                @if ($appointment->user)
                    <a class="button ghost" href="{{ route('admin.clients.show', $appointment->user) }}">Client</a>
                @endif
            </div>
            <div class="admin-list">
                <div><strong>Client</strong><span>{{ $appointment->user?->name ?? 'Non lie' }} - {{ $appointment->user?->email }}</span></div>
                <div><strong>Date</strong><span>{{ $appointment->starts_at?->format('d/m/Y H:i') ?: 'A confirmer' }}</span></div>
                <div><strong>Durée</strong><span>{{ $appointment->duration_minutes }} minutes</span></div>
                <div><strong>Canal</strong><span>{{ $appointment->channel }}</span></div>
                <div><strong>Demande source</strong><span>{{ $appointment->leadRequest?->topic ?? 'Aucune' }}</span></div>
                <div><strong>Notes</strong><span>{{ $appointment->notes ?: 'Aucune note' }}</span></div>
            </div>
        </article>

        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Action admin</h2>
                <span>Confirmer, reporter ou annuler</span>
            </div>
            @if (session('status'))
                <p class="form-note" data-state="success">{{ session('status') }}</p>
            @endif
            <form class="lead-form admin-form" method="post" action="{{ route('admin.appointments.update', $appointment) }}">
                @csrf
                @method('PATCH')
                <label>Sujet<input name="topic" value="{{ old('topic', $appointment->topic) }}" required></label>
                <div class="form-grid">
                    <label>Date et heure<input type="datetime-local" name="starts_at" value="{{ old('starts_at', $appointment->starts_at?->format('Y-m-d\TH:i')) }}"></label>
                    <label>Durée
                        <select name="duration_minutes" required>
                            @foreach ([30, 45, 60, 90] as $duration)
                                <option value="{{ $duration }}" @selected((int) $appointment->duration_minutes === $duration)>{{ $duration }} min</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="form-grid">
                    <label>Canal<input name="channel" value="{{ old('channel', $appointment->channel) }}" required></label>
                    <label>Statut
                        <select name="status" required>
                            @foreach ($statuses as $value => $label)
                                <option value="{{ $value }}" @selected($appointment->status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <label>Note<textarea name="notes" rows="6" placeholder="Instructions pour le client, lien de reunion, raison du report...">{{ old('notes', $appointment->notes) }}</textarea></label>
                @if ($errors->any())
                    <p class="form-note" data-state="error">{{ $errors->first() }}</p>
                @endif
                <button class="button primary" type="submit">Enregistrer</button>
            </form>
        </article>
    </section>
</x-admin.layout>
