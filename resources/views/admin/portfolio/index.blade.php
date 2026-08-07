<x-admin.layout title="Portfolio">
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <span>Portfolio</span>
                <h1>Événements, forums et presse</h1>
            </div>
            <a class="admin-btn" href="{{ route('admin.portfolio.create') }}">Ajouter</a>
        </div>

        @if (session('status'))
            <p class="admin-alert">{{ session('status') }}</p>
        @endif

        <div class="admin-list">
            @forelse ($items as $item)
                <article class="admin-list-card">
                    <div>
                        <strong>{{ $item->title }}</strong>
                        <p>{{ ucfirst($item->type) }}{{ $item->event_date ? ' - '.$item->event_date->format('d/m/Y') : '' }}{{ $item->location ? ' - '.$item->location : '' }}</p>
                    </div>
                    <span>{{ $item->is_published ? 'Publié' : 'Brouillon' }}</span>
                    <div class="admin-row-actions">
                        <a class="admin-link" href="{{ route('admin.portfolio.edit', $item) }}">Modifier</a>
                        <form method="POST" action="{{ route('admin.portfolio.destroy', $item) }}">
                            @csrf
                            @method('DELETE')
                            <button class="admin-link danger" type="submit">Supprimer</button>
                        </form>
                    </div>
                </article>
            @empty
                <p class="admin-empty">Aucune publication portfolio pour le moment.</p>
            @endforelse
        </div>

        {{ $items->links() }}
    </section>
</x-admin.layout>
