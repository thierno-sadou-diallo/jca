<x-admin.layout title="Nouveau paiement">
    <section class="admin-panel admin-form-panel">
        <div class="admin-panel-head">
            <h2>Creer un paiement</h2>
            <span>Facture, acompte ou frais de consultation</span>
        </div>
        <form class="lead-form admin-form" method="post" action="{{ route('admin.payments.store') }}">
            @include('admin.payments._form', ['submitLabel' => 'Creer'])
        </form>
    </section>
</x-admin.layout>
