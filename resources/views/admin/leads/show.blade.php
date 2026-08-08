<x-admin.layout title="Traiter une demande">
    <section class="admin-detail-grid">
        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>{{ $lead->name }}</h2>
                <span>{{ $lead->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="admin-list">
                <div><strong>Email</strong><span>{{ $lead->email }}</span></div>
                <div><strong>Téléphone</strong><span>{{ $lead->phone ?: 'Non indiqué' }}</span></div>
                <div><strong>Motif</strong><span>{{ $lead->topic }}</span></div>
                <div>
                    <strong>Rendez-vous demandé</strong>
                    <span>
                        @if ($lead->preferred_date)
                            {{ $lead->preferred_date->format('d/m/Y') }}
                            @if (! empty($lead->payload['appointment_requested_at']))
                                à {{ \Carbon\Carbon::parse($lead->payload['appointment_requested_at'])->format('H:i') }}
                            @endif
                        @else
                            Non indiqué
                        @endif
                    </span>
                </div>
                <div><strong>Canal préféré</strong><span>{{ $lead->preferred_channel ?: 'Email' }}</span></div>
                <div><strong>Message</strong><span>{{ $lead->message }}</span></div>
                <div><strong>Documents joints à cette demande</strong><span>{{ $lead->documents->count() }} document(s)</span></div>
                @foreach ($lead->documents as $document)
                    <div>
                        <strong>{{ $document->title }}</strong>
                        <span>{{ $document->type }} - {{ \App\Models\Document::statuses()[$document->status] ?? $document->status }} - {{ $document->created_at->format('d/m/Y H:i') }}</span>
                        <a class="admin-link" href="{{ route('admin.documents.download', $document) }}">Télécharger</a>
                    </div>
                @endforeach
                <div><strong>Autres documents du client</strong><span>{{ $clientDocuments->count() }} document(s) dans son espace</span></div>
                @foreach ($clientDocuments as $document)
                    <div>
                        <strong>{{ $document->title }}</strong>
                        <span>{{ $document->type }} - {{ \App\Models\Document::statuses()[$document->status] ?? $document->status }} - {{ $document->created_at->format('d/m/Y H:i') }}</span>
                        <a class="admin-link" href="{{ route('admin.documents.download', $document) }}">Télécharger</a>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Action</h2>
                <span>Statut actuel: {{ $lead->status }}</span>
            </div>
            <a class="button ghost" href="{{ route('admin.immigration-cases.create', ['lead_id' => $lead->id]) }}">Convertir en dossier immigration</a>
            @if (session('status'))
                <p class="form-note" data-state="success">{{ session('status') }}</p>
            @endif
            <form class="lead-form admin-form" method="post" action="{{ route('admin.leads.update', $lead) }}">
                @csrf
                @method('PATCH')
                <label>Statut
                    <select name="status" required>
                        @foreach (['new' => 'Nouveau', 'in_review' => 'En analyse', 'contacted' => 'Contacté', 'converted' => 'Converti en dossier', 'closed' => 'Fermé'] as $value => $label)
                            <option value="{{ $value }}" @selected($lead->status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Note interne
                    <textarea name="admin_note" rows="6">{{ $lead->payload['admin_note'] ?? '' }}</textarea>
                </label>
                <label>Réponse professionnelle au client
                    <textarea name="response_message" rows="7" placeholder="Bonjour, nous avons bien reçu votre demande. Après analyse des éléments transmis, voici les prochaines étapes recommandées...">{{ $lead->payload['response_message'] ?? '' }}</textarea>
                </label>
                <button class="button primary" type="submit">Enregistrer</button>
            </form>
        </article>
    </section>
</x-admin.layout>
