<x-admin.layout title="Modifier l offre">
    <section class="admin-panel admin-form-panel">
        <div class="admin-panel-head">
            <h2>{{ $job->title }}</h2>
            <span>{{ $job->status }}</span>
        </div>
        @if (session('status'))
            <p class="form-note" data-state="success">{{ session('status') }}</p>
        @endif
        <form class="lead-form admin-form" method="post" action="{{ route('admin.jobs.update', $job) }}">
            @include('admin.jobs._form')
        </form>
    </section>
</x-admin.layout>
