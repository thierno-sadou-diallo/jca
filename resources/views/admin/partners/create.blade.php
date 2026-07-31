<x-admin.layout title="Nouveau partenaire">
    <section class="admin-panel admin-form-panel">
        <div class="admin-panel-head">
            <h2>Créer un partenaire</h2>
            <span>Reseau international JCA</span>
        </div>
        <form class="lead-form admin-form" method="post" action="{{ route('admin.partners.store') }}" enctype="multipart/form-data">
            @include('admin.partners._form', ['submitLabel' => 'Créer'])
        </form>
    </section>
</x-admin.layout>
