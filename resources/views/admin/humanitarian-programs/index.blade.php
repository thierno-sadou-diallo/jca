<x-admin.layout title="Programmes humanitaires">
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <h2>Programmes humanitaires</h2>
                <span>Inclusion, aide sociale, employabilite et actions solidaires</span>
            </div>
            <a class="button primary" href="{{ route('admin.humanitarian-programs.create') }}">Nouveau programme</a>
        </div>
        <form class="admin-filter" method="get" action="{{ route('admin.humanitarian-programs.index') }}">
            <select name="status">
                <option value="">Tous les statuts</option>
                @foreach (['draft' => 'Brouillon', 'active' => 'Actif', 'completed' => 'Termine', 'archived' => 'Archive'] as $value => $label)
                    <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="button primary" type="submit">Filtrer</button>
        </form>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Programme</th><th>Pays</th><th>Domaine</th><th>Statut</th><th>Derniere mise à jour</th><th></th></tr></thead>
                <tbody>
                    @forelse ($programs as $program)
                        <tr>
                            <td><strong>{{ $program->title }}</strong><span>{{ $program->slug }}</span></td>
                            <td>{{ $program->country ?: 'International' }}</td>
                            <td>{{ $program->focus_area ?: 'Non indiqué' }}</td>
                            <td><mark>{{ $program->status }}</mark></td>
                            <td>{{ $program->updated_at?->format('d/m/Y') }}</td>
                            <td><a class="admin-link" href="{{ route('admin.humanitarian-programs.edit', $program) }}">Modifier</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6">Aucun programme trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $programs->links() }}
    </section>
</x-admin.layout>
