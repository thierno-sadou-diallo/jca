<x-admin.layout title="Modifier le programme">
    <section class="admin-panel admin-form-panel">
        <div class="admin-panel-head">
            <div>
                <h2>{{ $program->title }}</h2>
                <span>{{ $program->focus_area ?: 'Programme humanitaire' }}</span>
            </div>
            <a class="button ghost" href="{{ route('admin.humanitarian-programs.index') }}">Retour</a>
        </div>
        @if (session('status'))
            <p class="form-note" data-state="success">{{ session('status') }}</p>
        @endif
        <form class="lead-form admin-form" method="post" action="{{ route('admin.humanitarian-programs.update', $program) }}" enctype="multipart/form-data">
            @method('PATCH')
            @include('admin.humanitarian-programs._form', ['submitLabel' => 'Enregistrer'])
        </form>
    </section>
</x-admin.layout>
