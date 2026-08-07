<x-admin.layout title="Dossiers immigration">
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <h2>Suivi des dossiers</h2>
                <span>Statuts, historique et prochaines étapes</span>
            </div>
            <a class="button primary" href="{{ route('admin.immigration-cases.create') }}">Nouveau dossier</a>
        </div>

        <form class="admin-filter wide-filter" method="get" action="{{ route('admin.immigration-cases.index') }}">
            <input name="q" value="{{ $query }}" placeholder="Référence, client, programme, pays">
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
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Client</th>
                        <th>Programme</th>
                        <th>Destination</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cases as $case)
                        <tr>
                            <td><strong>{{ $case->reference }}</strong><span>{{ $case->leadRequest?->topic ?: 'Dossier direct' }}</span></td>
                            <td><strong>{{ $case->user?->name ?? 'Client non lié' }}</strong><span>{{ $case->user?->email }}</span></td>
                            <td>{{ $case->program_type }}</td>
                            <td>{{ $case->destination_country ?: 'Non indiqué' }}</td>
                            <td><mark>{{ $statuses[$case->status] ?? $case->status }}</mark></td>
                            <td>{{ $case->created_at->format('d/m/Y') }}</td>
                            <td><a class="admin-link" href="{{ route('admin.immigration-cases.show', $case) }}">Ouvrir</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7">Aucun dossier immigration trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $cases->links() }}
    </section>
</x-admin.layout>
