<x-admin.layout title="Candidatures">
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <h2>Candidatures emploi</h2>
                <span>CV, préqualification et suivi des talents</span>
            </div>
            <form class="admin-filter wide-filter" method="get" action="{{ route('admin.applications.index') }}">
                <input name="q" value="{{ $query }}" placeholder="Candidat, email ou offre">
                <select name="status">
                    <option value="">Tous les statuts</option>
                    @foreach ($statuses as $value => $label)
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
                        <th>Candidat</th>
                        <th>Offre</th>
                        <th>Pays</th>
                        <th>Statut</th>
                        <th>Revue</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applications as $application)
                        <tr>
                            <td><strong>{{ $application->name }}</strong><span>{{ $application->email }} {{ $application->phone ? '- '.$application->phone : '' }}</span></td>
                            <td>{{ $application->jobPosting?->title ?? 'Offre supprimee' }}</td>
                            <td>{{ $application->country ?: 'Non indiqué' }}</td>
                            <td><mark>{{ $statuses[$application->status] ?? $application->status }}</mark></td>
                            <td>{{ $application->reviewer?->name ?? 'Non revue' }}</td>
                            <td>{{ $application->created_at->format('d/m/Y H:i') }}</td>
                            <td><a class="admin-link" href="{{ route('admin.applications.show', $application) }}">Traiter</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7">Aucune candidature trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $applications->links() }}
    </section>
</x-admin.layout>
