<x-admin.layout title="Partenaires">
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <h2>Partenaires</h2>
                <span>Institutions, universites, entreprises, ONG et gouvernements</span>
            </div>
            <a class="button primary" href="{{ route('admin.partners.create') }}">Nouveau partenaire</a>
        </div>
        <form class="admin-filter wide-filter" method="get" action="{{ route('admin.partners.index') }}">
            <select name="type">
                <option value="">Tous les types</option>
                @foreach ($types as $item)
                    <option value="{{ $item }}" @selected($type === $item)>{{ $item }}</option>
                @endforeach
            </select>
            <select name="country">
                <option value="">Tous les pays</option>
                @foreach ($countries as $item)
                    <option value="{{ $item }}" @selected($country === $item)>{{ $item }}</option>
                @endforeach
            </select>
            <button class="button primary" type="submit">Filtrer</button>
        </form>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Partenaire</th><th>Type</th><th>Pays</th><th>Site</th><th>Mis en avant</th><th></th></tr></thead>
                <tbody>
                    @forelse ($partners as $partner)
                        <tr>
                            <td><strong>{{ $partner->name }}</strong><span>{{ str($partner->summary)->limit(120) }}</span></td>
                            <td>{{ $partner->type ?: 'Non indiqué' }}</td>
                            <td>{{ $partner->country ?: 'International' }}</td>
                            <td>{{ $partner->website ?: 'Non indiqué' }}</td>
                            <td><mark>{{ $partner->is_featured ? 'Oui' : 'Non' }}</mark></td>
                            <td><a class="admin-link" href="{{ route('admin.partners.edit', $partner) }}">Modifier</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6">Aucun partenaire trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $partners->links() }}
    </section>
</x-admin.layout>
