<x-admin.layout title="Fiche client">
    <section class="admin-stats admin-stats-clean">
        <article><span>Demandes</span><strong>{{ $stats['leads'] }}</strong></article>
        <article><span>Documents</span><strong>{{ $stats['documents'] }}</strong></article>
        <article><span>Dossiers</span><strong>{{ $stats['immigrationCases'] }}</strong></article>
        <article><span>Rendez-vous</span><strong>{{ $stats['appointments'] }}</strong></article>
        <article><span>Messages</span><strong>{{ $stats['messages'] }}</strong></article>
    </section>

    <section class="admin-detail-grid">
        <article class="admin-panel">
            <div class="admin-panel-head">
                <div>
                    <h2>{{ $client->name }}</h2>
                    <span>{{ $client->email }} {{ $client->phone ? '- '.$client->phone : '' }}</span>
                </div>
                <div class="form-actions">
                    <a class="button ghost" href="{{ route('admin.messages.show', $client) }}">Message</a>
                    <a class="button primary" href="{{ route('admin.immigration-cases.create', ['client_id' => $client->id]) }}">Nouveau dossier</a>
                </div>
            </div>

            <div class="admin-list">
                <div><strong>Type de client</strong><span>{{ $client->profile?->type_client ?? 'Particulier' }}</span></div>
                <div><strong>Organisation</strong><span>{{ $client->profile?->organization_name ?: 'Non indiqué' }}</span></div>
                <div><strong>Pays / ville</strong><span>{{ $client->profile?->country ?: 'Non indiqué' }}{{ $client->profile?->city ? ' - '.$client->profile->city : '' }}</span></div>
                <div><strong>Langue préférée</strong><span>{{ strtoupper($client->profile?->preferred_language ?? 'fr') }}</span></div>
                <div><strong>Statut du compte</strong><span>{{ $client->status }}</span></div>
                <div><strong>Création</strong><span>{{ $client->created_at->format('d/m/Y H:i') }}</span></div>
            </div>
        </article>

        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Modifier le profil</h2>
                <span>Qualification administrative</span>
            </div>
            @if (session('status'))
                <p class="form-note" data-state="success">{{ session('status') }}</p>
            @endif
            <form class="lead-form admin-form" method="post" action="{{ route('admin.clients.update', $client) }}">
                @csrf
                @method('PATCH')
                <label>Statut
                    <select name="status" required>
                        @foreach (['active' => 'Actif', 'inactive' => 'Inactif', 'suspended' => 'Suspendu'] as $value => $label)
                            <option value="{{ $value }}" @selected($client->status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Type de client
                    <select name="type_client" required>
                        @foreach ($clientTypes as $type)
                            <option value="{{ $type }}" @selected(($client->profile?->type_client ?? 'Particulier') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Organisation<input name="organization_name" value="{{ old('organization_name', $client->profile?->organization_name) }}"></label>
                <div class="form-grid">
                    <label>Pays<input name="country" value="{{ old('country', $client->profile?->country) }}"></label>
                    <label>Ville<input name="city" value="{{ old('city', $client->profile?->city) }}"></label>
                </div>
                @if ($errors->any())
                    <p class="form-note" data-state="error">{{ $errors->first() }}</p>
                @endif
                <button class="button primary" type="submit">Enregistrer</button>
            </form>
        </article>
    </section>

    <section class="admin-grid">
        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Demandes recentes</h2>
                <span>Suivi du dossier</span>
            </div>
            <div class="admin-list">
                @forelse ($leads as $lead)
                    <div>
                        <strong>{{ $lead->topic }} - {{ $lead->status }}</strong>
                        <span>{{ $lead->created_at->format('d/m/Y H:i') }}</span>
                        <a class="admin-link" href="{{ route('admin.leads.show', $lead) }}">Traiter</a>
                    </div>
                @empty
                    <div><strong>Aucune demande</strong><span>Les demandes du client apparaîtront ici.</span></div>
                @endforelse
            </div>
        </article>

        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Documents</h2>
                <span>Pièces deposees</span>
            </div>
            <div class="admin-list">
                @forelse ($documents as $document)
                    <div>
                        <strong>{{ $document->title }}</strong>
                        <span>{{ $document->type }} - {{ \App\Models\Document::statuses()[$document->status] ?? $document->status }} - {{ $document->created_at->format('d/m/Y H:i') }}</span>
                        <a class="admin-link" href="{{ route('admin.documents.download', $document) }}">Télécharger</a>
                    </div>
                @empty
                    <div><strong>Aucun document</strong><span>Le client n’a pas encore déposé de pièce.</span></div>
                @endforelse
            </div>
        </article>
    </section>

    <section class="admin-grid">
        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Dossiers immigration</h2>
                <span>Suivi actif</span>
            </div>
            <div class="admin-list">
                @forelse ($immigrationCases as $case)
                    <div>
                        <strong>{{ $case->reference }}</strong>
                        <span>{{ $case->program_type }} - {{ \App\Models\ImmigrationCase::statuses()[$case->status] ?? $case->status }}</span>
                        <a class="admin-link" href="{{ route('admin.immigration-cases.show', $case) }}">Ouvrir</a>
                    </div>
                @empty
                    <div><strong>Aucun dossier</strong><span>Aucun dossier immigration associé à ce client.</span></div>
                @endforelse
            </div>
        </article>

        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Candidatures</h2>
                <span>Emploi international</span>
            </div>
            <div class="admin-list">
                @forelse ($applications as $application)
                    <div>
                        <strong>{{ $application->jobPosting?->title ?? 'Offre supprimée' }}</strong>
                        <span>{{ \App\Models\JobApplication::statuses()[$application->status] ?? $application->status }} - {{ $application->created_at->format('d/m/Y') }}</span>
                        <a class="admin-link" href="{{ route('admin.applications.show', $application) }}">Traiter</a>
                    </div>
                @empty
                    <div><strong>Aucune candidature</strong><span>Aucune candidature associée à ce client.</span></div>
                @endforelse
            </div>
        </article>
    </section>

    <section class="admin-grid">
        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Rendez-vous</h2>
                <span>Historique</span>
            </div>
            <div class="admin-list">
                @forelse ($appointments as $appointment)
                    <div><strong>{{ $appointment->topic }}</strong><span>{{ $appointment->starts_at ?: 'Date à confirmer' }} - {{ $appointment->status }}</span></div>
                @empty
                    <div><strong>Aucun rendez-vous</strong><span>Aucun rendez-vous associé à ce client.</span></div>
                @endforelse
            </div>
        </article>

        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Messages recents</h2>
                <span>Conversation</span>
            </div>
            <div class="admin-list">
                @forelse ($messages as $message)
                    <div>
                        <strong>{{ $message->subject }}</strong>
                        <span>{{ $message->created_at->format('d/m/Y H:i') }}</span>
                        <p>{{ str($message->body)->limit(130) }}</p>
                    </div>
                @empty
                    <div><strong>Aucun message</strong><span>Démarrez une conversation avec ce client.</span></div>
                @endforelse
            </div>
        </article>
    </section>
</x-admin.layout>
