<x-admin.layout title="Traiter une candidature">
    <section class="admin-detail-grid">
        <article class="admin-panel">
            <div class="admin-panel-head">
                <div>
                    <h2>{{ $application->name }}</h2>
                    <span>{{ $application->email }} {{ $application->phone ? '- '.$application->phone : '' }}</span>
                </div>
                @if ($application->user)
                    <a class="button ghost" href="{{ route('admin.clients.show', $application->user) }}">Client</a>
                @endif
            </div>
            <div class="admin-list">
                <div><strong>Offre</strong><span>{{ $application->jobPosting?->title ?? 'Offre supprimee' }}</span></div>
                <div><strong>Entreprise</strong><span>{{ $application->jobPosting?->company_name ?: 'Entreprise partenaire' }}</span></div>
                <div><strong>Pays</strong><span>{{ $application->country ?: 'Non indique' }}</span></div>
                <div><strong>Message</strong><span>{{ $application->message ?: 'Aucun message' }}</span></div>
                <div><strong>Statut</strong><span>{{ $statuses[$application->status] ?? $application->status }}</span></div>
                <div><strong>CV</strong><span>{{ $application->resume_path ? 'Fichier disponible' : 'Aucun CV' }}</span>
                    @if ($application->resume_path)
                        <a class="admin-link" href="{{ route('admin.applications.download', $application) }}">Telecharger le CV</a>
                    @endif
                </div>
            </div>
        </article>

        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Action</h2>
                <span>Prequalification</span>
            </div>
            @if (session('application_review_status'))
                <p class="form-note" data-state="success">{{ session('application_review_status') }}</p>
            @endif
            <form class="lead-form admin-form" method="post" action="{{ route('admin.applications.update', $application) }}">
                @csrf
                @method('PATCH')
                <label>Statut
                    <select name="status" required>
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}" @selected($application->status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Note admin / retour candidat
                    <textarea name="admin_note" rows="7" placeholder="Indiquez les prochaines etapes, pieces attendues ou raisons de refus.">{{ old('admin_note', $application->admin_note) }}</textarea>
                </label>
                @if ($errors->any())
                    <p class="form-note" data-state="error">{{ $errors->first() }}</p>
                @endif
                <button class="button primary" type="submit">Enregistrer</button>
            </form>
        </article>
    </section>
</x-admin.layout>
