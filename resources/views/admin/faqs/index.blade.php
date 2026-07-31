<x-admin.layout title="FAQ">
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <h2>Questions fréquentes</h2>
                <span>Réponses publiques organisees par categorie</span>
            </div>
            <a class="button primary" href="{{ route('admin.faqs.create') }}">Nouvelle FAQ</a>
        </div>
        <form class="admin-filter" method="get" action="{{ route('admin.faqs.index') }}">
            <select name="category">
                <option value="">Toutes les categories</option>
                @foreach ($categories as $item)
                    <option value="{{ $item }}" @selected($category === $item)>{{ $item }}</option>
                @endforeach
            </select>
            <button class="button primary" type="submit">Filtrer</button>
        </form>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Question</th><th>Catégorie</th><th>Ordre</th><th>Publiée</th><th></th></tr></thead>
                <tbody>
                    @forelse ($faqs as $faq)
                        <tr>
                            <td><strong>{{ $faq->question }}</strong><span>{{ str($faq->answer)->limit(120) }}</span></td>
                            <td>{{ $faq->category ?: 'General' }}</td>
                            <td>{{ $faq->sort_order }}</td>
                            <td><mark>{{ $faq->is_published ? 'Oui' : 'Non' }}</mark></td>
                            <td><a class="admin-link" href="{{ route('admin.faqs.edit', $faq) }}">Modifier</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5">Aucune FAQ trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $faqs->links() }}
    </section>
</x-admin.layout>
