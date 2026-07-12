<x-admin.layout title="Modifier le paiement">
    <section class="admin-panel admin-form-panel">
        <div class="admin-panel-head">
            <div>
                <h2>{{ $payment->reference }}</h2>
                <span>{{ $statuses[$payment->status] ?? $payment->status }}</span>
            </div>
            <a class="button ghost" href="{{ route('admin.payments.index') }}">Retour</a>
        </div>
        @if (session('status'))
            <p class="form-note" data-state="success">{{ session('status') }}</p>
        @endif
        <form class="lead-form admin-form" method="post" action="{{ route('admin.payments.update', $payment) }}">
            @method('PATCH')
            @include('admin.payments._form', ['submitLabel' => 'Enregistrer'])
        </form>
    </section>
</x-admin.layout>
