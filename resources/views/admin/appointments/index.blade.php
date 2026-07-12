<x-admin.layout title="Rendez-vous">
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <h2>Rendez-vous clients</h2>
                <span>Acceptation, report, refus et annulation</span>
            </div>
            <a class="button ghost" href="{{ route('admin.availability.index') }}">Disponibilites</a>
        </div>
        <form class="admin-filter wide-filter" method="get" action="{{ route('admin.appointments.index') }}">
            <input name="q" value="{{ $query }}" placeholder="Client, email, sujet">
            <select name="status">
                <option value="">Tous les statuts</option>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="button primary" type="submit">Filtrer</button>
        </form>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Client</th><th>Sujet</th><th>Date</th><th>Canal</th><th>Statut</th><th></th></tr></thead>
                <tbody>
                    @forelse ($appointments as $appointment)
                        <tr>
                            <td><strong>{{ $appointment->user?->name ?? 'Client non lie' }}</strong><span>{{ $appointment->user?->email }}</span></td>
                            <td>{{ $appointment->topic }}</td>
                            <td>{{ $appointment->starts_at?->format('d/m/Y H:i') ?: 'A confirmer' }}</td>
                            <td>{{ $appointment->channel }}</td>
                            <td><mark>{{ $statuses[$appointment->status] ?? $appointment->status }}</mark></td>
                            <td><a class="admin-link" href="{{ route('admin.appointments.show', $appointment) }}">Traiter</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6">Aucun rendez-vous trouve.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $appointments->links() }}
    </section>
</x-admin.layout>
