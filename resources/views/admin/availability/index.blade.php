<x-admin.layout title="Disponibilites">
    <section class="admin-detail-grid">
        <article class="admin-panel">
            <div class="admin-panel-head">
                <div>
                    <h2>Ajouter un creneau</h2>
                    <span>Visible au client pour la semaine actuelle et la semaine prochaine</span>
                </div>
            </div>
            @if (session('status'))
                <p class="form-note" data-state="success">{{ session('status') }}</p>
            @endif
            <form class="lead-form admin-form" method="post" action="{{ route('admin.availability.store') }}">
                @csrf
                <div class="form-grid">
                    <label>Date<input type="date" name="date" required></label>
                    <label>Heure<input type="time" name="time" required></label>
                </div>
                <label>Duree
                    <select name="duration_minutes" required>
                        <option value="30">30 minutes</option>
                        <option value="45" selected>45 minutes</option>
                        <option value="60">60 minutes</option>
                        <option value="90">90 minutes</option>
                    </select>
                </label>
                <label>Note interne<input name="notes" placeholder="Ex: consultation immigration, urgence, visio..."></label>
                @if ($errors->any())
                    <p class="form-note" data-state="error">{{ $errors->first() }}</p>
                @endif
                <button class="button primary" type="submit">Ajouter la disponibilite</button>
            </form>
        </article>

        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Creneaux a venir</h2>
                <span>Disponibles ou reserves</span>
            </div>
            <div class="admin-list">
                @forelse ($slots as $slot)
                    <div>
                        <strong>{{ \Carbon\Carbon::parse($slot->starts_at)->format('d/m/Y H:i') }}</strong>
                        <span>{{ $slot->status }} - fin {{ \Carbon\Carbon::parse($slot->ends_at)->format('H:i') }}</span>
                        @if ($slot->status === 'available')
                            <form method="post" action="{{ route('admin.availability.destroy', $slot->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="button ghost" type="submit">Supprimer</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div><strong>Aucun creneau</strong><span>Ajoutez vos disponibilites pour ouvrir les reservations.</span></div>
                @endforelse
            </div>
            {{ $slots->links() }}
        </article>
    </section>
</x-admin.layout>
