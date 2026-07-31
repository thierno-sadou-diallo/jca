<x-admin.layout title="Nouvelle FAQ">
    <section class="admin-panel admin-form-panel">
        <div class="admin-panel-head">
            <h2>Créer une FAQ</h2>
            <span>Contenu public</span>
        </div>
        <form class="lead-form admin-form" method="post" action="{{ route('admin.faqs.store') }}">
            @include('admin.faqs._form', ['submitLabel' => 'Créer'])
        </form>
    </section>
</x-admin.layout>
