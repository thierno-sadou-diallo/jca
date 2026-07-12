<x-admin.layout title="Emplois">
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <h2>Offres d emploi</h2>
                <span>Creation, publication et suivi des opportunites</span>
            </div>
            <a class="button primary" href="{{ route('admin.jobs.create') }}">Nouvelle offre</a>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Offre</th>
                        <th>Pays</th>
                        <th>Secteur</th>
                        <th>Statut</th>
                        <th>Publication</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jobs as $job)
                        <tr>
                            <td><strong>{{ $job->title }}</strong><span>{{ $job->company_name ?: 'Entreprise partenaire' }}</span></td>
                            <td>{{ $job->country }}</td>
                            <td>{{ $job->sector }}</td>
                            <td><mark>{{ $job->status }}</mark></td>
                            <td>{{ optional($job->published_at)->format('d/m/Y') ?: '-' }}</td>
                            <td><a class="admin-link" href="{{ route('admin.jobs.edit', $job) }}">Modifier</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6">Aucune offre pour le moment.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $jobs->links() }}
    </section>
</x-admin.layout>
