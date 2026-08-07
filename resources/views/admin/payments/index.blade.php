<x-admin.layout title="Paiements">
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <h2>Paiements et facturation</h2>
                <span>Suivi des montants, statuts et références clients</span>
            </div>
            <a class="button primary" href="{{ route('admin.payments.create') }}">Nouveau paiement</a>
        </div>
        <form class="admin-filter" method="get" action="{{ route('admin.payments.index') }}">
            <select name="status">
                <option value="">Tous les statuts</option>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="button primary" type="submit">Filtrer</button>
        </form>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Référence</th><th>Client</th><th>Montant</th><th>Statut</th><th>Prestataire</th><th></th></tr></thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr>
                            <td><strong>{{ $payment->reference }}</strong><span>{{ $payment->created_at->format('d/m/Y H:i') }}</span></td>
                            <td>{{ $payment->user?->name ?? 'Client supprimé' }}</td>
                            <td>{{ number_format((float) $payment->amount, 2, ',', ' ') }} {{ $payment->currency }}</td>
                            <td><mark>{{ $statuses[$payment->status] ?? $payment->status }}</mark></td>
                            <td>{{ $payment->provider ?: 'Manuel' }}</td>
                            <td><a class="admin-link" href="{{ route('admin.payments.edit', $payment) }}">Modifier</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6">Aucun paiement trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $payments->links() }}
    </section>
</x-admin.layout>
