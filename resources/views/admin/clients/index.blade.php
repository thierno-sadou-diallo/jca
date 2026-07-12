<x-admin.layout title="Clients">
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <h2>Gestion des clients</h2>
                <span>Particuliers, candidats, entreprises, ONG, institutions et partenaires</span>
            </div>
            <form class="admin-filter wide-filter" method="get" action="{{ route('admin.clients.index') }}">
                <input name="q" value="{{ $query }}" placeholder="Nom, email, telephone">
                <select name="type_client">
                    <option value="">Tous les types</option>
                    @foreach ($clientTypes as $type)
                        <option value="{{ $type }}" @selected($typeClient === $type)>{{ $type }}</option>
                    @endforeach
                </select>
                <select name="status">
                    <option value="">Tous les statuts</option>
                    @foreach (['active' => 'Actif', 'inactive' => 'Inactif', 'suspended' => 'Suspendu'] as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="button primary" type="submit">Filtrer</button>
            </form>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Type</th>
                        <th>Organisation</th>
                        <th>Pays</th>
                        <th>Documents</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                        <tr>
                            <td><strong>{{ $client->name }}</strong><span>{{ $client->email }} {{ $client->phone ? '- '.$client->phone : '' }}</span></td>
                            <td>{{ $client->profile?->type_client ?? 'Particulier' }}</td>
                            <td>{{ $client->profile?->organization_name ?: 'Non indique' }}</td>
                            <td>{{ $client->profile?->country ?: 'Non indique' }}</td>
                            <td><strong>{{ $client->documents_count }}</strong><span>piece(s)</span></td>
                            <td><mark>{{ $client->status }}</mark></td>
                            <td>{{ $client->created_at->format('d/m/Y') }}</td>
                            <td><a class="admin-link" href="{{ route('admin.clients.show', $client) }}">Ouvrir</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="8">Aucun client trouve.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $clients->links() }}
    </section>
</x-admin.layout>
