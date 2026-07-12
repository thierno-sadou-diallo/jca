<x-admin.layout title="Modifier l'article">
    <section class="admin-panel admin-form-panel">
        <div class="admin-panel-head">
            <div>
                <h2>{{ $article->title }}</h2>
                <span>{{ $article->slug }}</span>
            </div>
            <a class="button ghost" href="{{ route('admin.articles.index') }}">Retour</a>
        </div>
        @if (session('status'))
            <p class="form-note" data-state="success">{{ session('status') }}</p>
        @endif
        <form class="lead-form admin-form" method="post" action="{{ route('admin.articles.update', $article) }}">
            @method('PATCH')
            @include('admin.articles._form', ['submitLabel' => 'Enregistrer'])
        </form>
    </section>
</x-admin.layout>
