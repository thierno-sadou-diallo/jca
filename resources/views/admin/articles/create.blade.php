<x-admin.layout title="Nouvel article">
    <section class="admin-panel admin-form-panel">
        <div class="admin-panel-head">
            <h2>Créer un article</h2>
            <span>Publication editoriale</span>
        </div>
        <form class="lead-form admin-form" method="post" action="{{ route('admin.articles.store') }}">
            @include('admin.articles._form', ['submitLabel' => 'Créer'])
        </form>
    </section>
</x-admin.layout>
