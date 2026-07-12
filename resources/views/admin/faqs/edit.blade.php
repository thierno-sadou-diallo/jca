<x-admin.layout title="Modifier la FAQ">
    <section class="admin-panel admin-form-panel">
        <div class="admin-panel-head">
            <div>
                <h2>{{ $faq->question }}</h2>
                <span>{{ $faq->category ?: 'General' }}</span>
            </div>
            <a class="button ghost" href="{{ route('admin.faqs.index') }}">Retour</a>
        </div>
        @if (session('status'))
            <p class="form-note" data-state="success">{{ session('status') }}</p>
        @endif
        <form class="lead-form admin-form" method="post" action="{{ route('admin.faqs.update', $faq) }}">
            @method('PATCH')
            @include('admin.faqs._form', ['submitLabel' => 'Enregistrer'])
        </form>
    </section>
</x-admin.layout>
