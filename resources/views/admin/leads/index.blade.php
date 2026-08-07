<x-admin.layout title="Demandes">
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <h2>Demandes clients</h2>
                <span>Contact, consultation, partenariat et besoins entrants</span>
            </div>
            <form class="admin-filter" method="get" action="{{ route('admin.leads.index') }}">
                <select name="status">
                    <option value="">Tous les statuts</option>
                    @foreach (['new' => 'Nouveau', 'in_review' => 'Analyse', 'contacted' => 'Contacté', 'converted' => 'Converti', 'closed' => 'Fermé'] as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="button primary" type="submit">Filtrer</button>
            </form>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Contact</th>
                        <th>Motif</th>
                        <th>Rendez-vous demande</th>
                        <th>Documents</th>
                        <th>Statut</th>
                        <th>Canal</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leads as $lead)
                        <tr>
                            <td><strong>{{ $lead->name }}</strong><span>{{ $lead->email }} {{ $lead->phone ? '- '.$lead->phone : '' }}</span></td>
                            <td>{{ $lead->topic }}</td>
                            <td>
                                @if ($lead->preferred_date)
                                    <strong>{{ $lead->preferred_date->format('d/m/Y') }}</strong>
                                    @if (! empty($lead->payload['appointment_requested_at']))
                                        <span>{{ \Carbon\Carbon::parse($lead->payload['appointment_requested_at'])->format('H:i') }}</span>
                                    @endif
                                @else
                                    <span>Non indiqué</span>
                                @endif
                            </td>
                            <td><strong>{{ $lead->documents_count }}</strong><span>pièce(s)</span></td>
                            <td><mark>{{ $lead->status }}</mark></td>
                            <td>{{ $lead->preferred_channel ?: 'Email' }}</td>
                            <td>{{ $lead->created_at->format('d/m/Y H:i') }}</td>
                            <td><a class="admin-link" href="{{ route('admin.leads.show', $lead) }}">Traiter</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="8">Aucune demande trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $leads->links() }}
    </section>
</x-admin.layout>
