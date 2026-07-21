<x-admin.layout title="Tableau de bord">
    <section class="admin-welcome">
        <div>
            <span class="eyebrow">Centre de pilotage</span>
            <h2>Une vue claire pour prendre les bonnes decisions.</h2>
            <p>Retrouvez uniquement les priorites utiles pour traiter les clients rapidement.</p>
        </div>
        <div class="admin-welcome-card">
            <span>Priorite immediate</span>
            <strong>{{ $stats['newLeads'] + $stats['pendingDocuments'] + $stats['unreadMessages'] + $stats['pendingClients'] }}</strong>
            <p>Comptes, demandes, documents ou messages qui attendent une action.</p>
        </div>
    </section>

    <section class="admin-stats admin-stats-clean admin-stats-featured">
        <article><span>Clients</span><strong>{{ $stats['clients'] }}</strong></article>
        <article><span>Comptes a activer</span><strong>{{ $stats['pendingClients'] }}</strong></article>
        <article><span>Demandes a traiter</span><strong>{{ $stats['newLeads'] }}</strong></article>
        <article><span>Documents attente</span><strong>{{ $stats['pendingDocuments'] }}</strong></article>
        <article><span>Messages non lus</span><strong>{{ $stats['unreadMessages'] }}</strong></article>
    </section>

    <section class="admin-actions">
        <a href="{{ route('admin.clients.index') }}">
            <strong>Clients</strong>
            <span>Consulter les profils, statuts, documents et conversations.</span>
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'client', 'status' => 'inactive']) }}">
            <strong>Comptes a activer</strong>
            <span>Valider les nouvelles inscriptions avant l acces au portail.</span>
        </a>
        <a href="{{ route('admin.leads.index') }}">
            <strong>Demandes clients</strong>
            <span>Lire, repondre, changer le statut et suivre les documents joints.</span>
        </a>
        <a href="{{ route('admin.documents.index') }}">
            <strong>Documents</strong>
            <span>Valider, rejeter ou demander une nouvelle version.</span>
        </a>
        <a href="{{ route('admin.immigration-cases.index') }}">
            <strong>Dossiers immigration</strong>
            <span>Suivre les statuts, l historique et les prochaines etapes.</span>
        </a>
        <a href="{{ route('admin.appointments.index') }}">
            <strong>Rendez-vous</strong>
            <span>Confirmer, reporter, refuser ou annuler les consultations.</span>
        </a>
        <a href="{{ route('admin.messages.index') }}">
            <strong>Messagerie</strong>
            <span>Lire les messages clients et repondre depuis le back-office.</span>
        </a>
    </section>

    <section class="admin-grid">
        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Dernieres demandes</h2>
                <span>Traitement prioritaire</span>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Motif</th>
                            <th>Statut</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestLeads as $lead)
                            <tr>
                                <td><strong>{{ $lead->name }}</strong><span>{{ $lead->email }}</span></td>
                                <td>{{ $lead->topic }}</td>
                                <td><mark>{{ $lead->status }}</mark></td>
                                <td><a class="admin-link" href="{{ route('admin.leads.show', $lead) }}">Repondre</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="4">Aucune demande pour le moment.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</x-admin.layout>
