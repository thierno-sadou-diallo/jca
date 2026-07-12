<x-admin.layout title="Avis clients">
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <h2>Avis et retours clients</h2>
                <span>Lire, repondre, publier ou supprimer</span>
            </div>
        </div>

        @if (session('status'))
            <p class="form-note" data-state="success">{{ session('status') }}</p>
        @endif

        <div class="review-admin-list">
            @forelse ($reviews as $review)
                <article class="admin-panel review-admin-card">
                    <div class="admin-panel-head">
                        <div>
                            <h2>{{ $review->author_name }}</h2>
                            <span>{{ $review->status }} - {{ $review->created_at }}</span>
                        </div>
                        <form method="post" action="{{ route('admin.reviews.destroy', $review->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="button ghost" type="submit">Supprimer</button>
                        </form>
                    </div>
                    <p>{{ $review->quote }}</p>
                    <form class="lead-form admin-form" method="post" action="{{ route('admin.reviews.update', $review->id) }}">
                        @csrf
                        @method('PATCH')
                        <label>Reponse admin
                            <textarea name="admin_response" rows="4">{{ $review->admin_response }}</textarea>
                        </label>
                        <label>Statut
                            <select name="status">
                                <option value="pending" @selected($review->status === 'pending')>En attente</option>
                                <option value="published" @selected($review->status === 'published')>Publie</option>
                                <option value="closed" @selected($review->status === 'closed')>Ferme</option>
                            </select>
                        </label>
                        <button class="button primary" type="submit">Enregistrer la reponse</button>
                    </form>
                </article>
            @empty
                <article class="empty-state">
                    <h2>Aucun avis pour le moment.</h2>
                    <p>Les avis envoyes par les clients depuis leur espace apparaitront ici.</p>
                </article>
            @endforelse
        </div>

        {{ $reviews->links() }}
    </section>
</x-admin.layout>
