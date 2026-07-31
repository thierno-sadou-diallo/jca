<x-admin.layout title="Nouveau projet coopération">
    <section class="admin-panel admin-form-panel">
        <div class="admin-panel-head">
            <h2>Créer un projet de coopération</h2>
            <span>Structurer une initiative institutionnelle ou territoriale</span>
        </div>
        <form class="lead-form admin-form" method="post" action="{{ route('admin.cooperation-projects.store') }}" enctype="multipart/form-data">
            @include('admin.cooperation-projects._form', ['submitLabel' => 'Créer'])
        </form>
    </section>
</x-admin.layout>
