<x-admin.layout title="Modifier le projet">
    <section class="admin-panel admin-form-panel">
        <div class="admin-panel-head">
            <div>
                <h2>{{ $project->title }}</h2>
                <span>{{ $project->sector ?: 'Projet de cooperation' }}</span>
            </div>
            <a class="button ghost" href="{{ route('admin.cooperation-projects.index') }}">Retour</a>
        </div>
        @if (session('status'))
            <p class="form-note" data-state="success">{{ session('status') }}</p>
        @endif
        <form class="lead-form admin-form" method="post" action="{{ route('admin.cooperation-projects.update', $project) }}" enctype="multipart/form-data">
            @method('PATCH')
            @include('admin.cooperation-projects._form', ['submitLabel' => 'Enregistrer'])
        </form>
    </section>
</x-admin.layout>
