<x-admin.layout title="Ajouter au portfolio">
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <span>Portfolio</span>
                <h1>Nouvelle publication</h1>
            </div>
            <a class="admin-link" href="{{ route('admin.portfolio.index') }}">Retour</a>
        </div>

        @include('admin.portfolio._form', [
            'item' => $item,
            'action' => route('admin.portfolio.store'),
            'method' => 'POST',
        ])
    </section>
</x-admin.layout>
