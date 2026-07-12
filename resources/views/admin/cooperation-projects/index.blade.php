<x-admin.layout title="Projets cooperation">
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <h2>Projets de cooperation</h2>
                <span>Gouvernance, financement, partenariats et impact territorial</span>
            </div>
            <a class="button primary" href="{{ route('admin.cooperation-projects.create') }}">Nouveau projet</a>
        </div>
        <form class="admin-filter" method="get" action="{{ route('admin.cooperation-projects.index') }}">
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
                <thead><tr><th>Projet</th><th>Pays</th><th>Secteur</th><th>Statut</th><th>Dates</th><th></th></tr></thead>
                <tbody>
                    @forelse ($projects as $project)
                        <tr>
                            <td><strong>{{ $project->title }}</strong><span>{{ $project->slug }}</span></td>
                            <td>{{ $project->country ?: 'International' }}</td>
                            <td>{{ $project->sector ?: 'Non indique' }}</td>
                            <td><mark>{{ $project->status }}</mark></td>
                            <td>{{ $project->starts_at?->format('d/m/Y') ?: 'A definir' }} - {{ $project->ends_at?->format('d/m/Y') ?: 'En cours' }}</td>
                            <td><a class="admin-link" href="{{ route('admin.cooperation-projects.edit', $project) }}">Modifier</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6">Aucun projet trouve.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $projects->links() }}
    </section>
</x-admin.layout>
