<x-admin.layout title="Articles">
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <h2>Articles et actualités</h2>
                <span>Blog, conseils et veille internationale</span>
            </div>
            <a class="button primary" href="{{ route('admin.articles.create') }}">Nouvel article</a>
        </div>
        <form class="admin-filter wide-filter" method="get" action="{{ route('admin.articles.index') }}">
            <input name="q" value="{{ $query }}" placeholder="Titre">
            <select name="status">
                <option value="">Tous les statuts</option>
                <option value="draft" @selected($status === 'draft')>Brouillon</option>
                <option value="published" @selected($status === 'published')>Publie</option>
            </select>
            <button class="button primary" type="submit">Filtrer</button>
        </form>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Titre</th><th>Statut</th><th>Auteur</th><th>Publication</th><th></th></tr></thead>
                <tbody>
                    @forelse ($articles as $article)
                        <tr>
                            <td><strong>{{ $article->title }}</strong><span>{{ $article->slug }}</span></td>
                            <td><mark>{{ $article->status }}</mark></td>
                            <td>{{ $article->author?->name ?? 'JCA' }}</td>
                            <td>{{ $article->published_at?->format('d/m/Y H:i') ?: 'Non publie' }}</td>
                            <td><a class="admin-link" href="{{ route('admin.articles.edit', $article) }}">Modifier</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5">Aucun article trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $articles->links() }}
    </section>
</x-admin.layout>
