<x-admin.layout title="Modifier le partenaire">
    <section class="admin-panel admin-form-panel">
        <div class="admin-panel-head">
            <div>
                <h2>{{ $partner->name }}</h2>
                <span>{{ $partner->type ?: 'Partenaire' }}</span>
            </div>
            <a class="button ghost" href="{{ route('admin.partners.index') }}">Retour</a>
        </div>
        @if (session('status'))
            <p class="form-note" data-state="success">{{ session('status') }}</p>
        @endif
        <form class="lead-form admin-form" method="post" action="{{ route('admin.partners.update', $partner) }}" enctype="multipart/form-data">
            @method('PATCH')
            @include('admin.partners._form', ['submitLabel' => 'Enregistrer'])
        </form>
    </section>
</x-admin.layout>
