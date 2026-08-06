<x-admin.layout title="Modifier le portfolio">
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <span>Portfolio</span>
                <h1>Modifier la publication</h1>
            </div>
            <a class="admin-link" href="{{ route('admin.portfolio.index') }}">Retour</a>
        </div>

        @include('admin.portfolio._form', [
            'item' => $item,
            'action' => route('admin.portfolio.update', $item),
            'method' => 'PUT',
        ])
    </section>
</x-admin.layout>
