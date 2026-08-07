<x-admin.layout title="Nouvelle offre">
    <section class="admin-panel admin-form-panel">
        <div class="admin-panel-head">
            <h2>Publier une opportunité</h2>
            <span>Visible sur le site quand le statut est publié</span>
        </div>
        <form class="lead-form admin-form" method="post" action="{{ route('admin.jobs.store') }}">
            @include('admin.jobs._form')
        </form>
    </section>
</x-admin.layout>
