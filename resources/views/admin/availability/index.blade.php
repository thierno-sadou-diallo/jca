<x-admin.layout title="Disponibilités">
    <section class="admin-detail-grid">
        <article class="admin-panel">
            <div class="admin-panel-head">
                <div>
                    <h2>Ajouter des créneaux</h2>
                    <span>Cochez les jours ouverts, puis appliquez une heure et une durée.</span>
                </div>
            </div>
            @if (session('status'))
                <p class="form-note" data-state="success">{{ session('status') }}</p>
            @endif
            <form class="lead-form admin-form" method="post" action="{{ route('admin.availability.store') }}">
                @csrf
                <div class="appointment-calendar appointment-calendar-admin" aria-label="Calendrier des disponibilités">
                    @foreach ($calendars as $calendar)
                        <section class="compact-calendar">
                            <h3>{{ $calendar['label'] }}</h3>
                            <div class="calendar-weekdays" aria-hidden="true">
                                <span>Lun</span>
                                <span>Mar</span>
                                <span>Mer</span>
                                <span>Jeu</span>
                                <span>Ven</span>
                                <span>Sam</span>
                                <span>Dim</span>
                            </div>
                            <div class="calendar-grid">
                                @foreach ($calendar['days'] as $day)
                                    @if ($day['blank'])
                                        <span class="calendar-empty" aria-hidden="true"></span>
                                    @else
                                        <label @class([
                                            'calendar-day calendar-choice',
                                            'is-disabled' => $day['isPast'],
                                            'is-active' => $day['slots']->isNotEmpty(),
                                            'is-today' => $day['isToday'],
                                            'is-weekend' => $day['isWeekend'],
                                        ])>
                                            <input type="checkbox" name="dates[]" value="{{ $day['date'] }}" @disabled($day['isPast'])>
                                            <strong>{{ $day['number'] }}</strong>
                                            <span class="calendar-dot-row" aria-label="{{ $day['slots']->count() }} créneau(x)">
                                                @forelse ($day['slots']->take(3) as $slot)
                                                    <i @class(['is-booked' => $slot['status'] !== 'available'])></i>
                                                @empty
                                                    <i></i>
                                                @endforelse
                                            </span>
                                            @if ($day['slots']->isNotEmpty())
                                                <em>{{ $day['slots']->pluck('time')->take(2)->join(', ') }}</em>
                                            @endif
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>
                <div class="form-grid">
                    <label>Heure<input type="time" name="time" required></label>
                    <label>Durée
                        <select name="duration_minutes" required>
                            <option value="30">30 minutes</option>
                            <option value="45" selected>45 minutes</option>
                            <option value="60">60 minutes</option>
                            <option value="90">90 minutes</option>
                        </select>
                    </label>
                </div>
                <label>Note interne<input name="notes" placeholder="Ex: consultation immigration, urgence, visio..."></label>
                @if ($errors->any())
                    <p class="form-note" data-state="error">{{ $errors->first() }}</p>
                @endif
                <button class="button primary" type="submit">Ajouter les disponibilités</button>
            </form>
        </article>

        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Créneaux à venir</h2>
                <span>Disponibles ou réservés</span>
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
                    <div><strong>Aucun créneau</strong><span>Ajoutez vos disponibilités pour ouvrir les réservations.</span></div>
                @endforelse
            </div>
            {{ $slots->links() }}
        </article>
    </section>
</x-admin.layout>
