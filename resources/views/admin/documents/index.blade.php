<x-admin.layout title="Documents">
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <h2>Gestion documentaire</h2>
                <span>Validation, rejet et demande de nouvelle version</span>
            </div>
            <form class="admin-filter wide-filter" method="get" action="{{ route('admin.documents.index') }}">
                <input name="q" value="{{ $query }}" placeholder="Client, titre ou type">
                <select name="status">
                    <option value="">Tous les statuts</option>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="button primary" type="submit">Filtrer</button>
            </form>
        </div>

        @if (session('document_review_status'))
            <p class="form-note" data-state="success">{{ session('document_review_status') }}</p>
        @endif

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Document</th>
                        <th>Client</th>
                        <th>Demande</th>
                        <th>Statut</th>
                        <th>Revue</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documents as $document)
                        <tr>
                            <td>
                                <strong>{{ $document->title }}</strong>
                                <span>{{ $document->type ?: 'Autre' }} - {{ $document->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td>
                                <strong>{{ $document->user?->name ?? 'Client non lie' }}</strong>
                                <span>{{ $document->user?->email ?? 'Email non disponible' }}</span>
                            </td>
                            <td>{{ $document->leadRequest?->topic ?? 'Document espace client' }}</td>
                            <td><mark>{{ $statuses[$document->status] ?? $document->status }}</mark></td>
                            <td>
                                <span>{{ $document->reviewer?->name ?? 'Non revue' }}</span>
                                @if ($document->reviewed_at)
                                    <span>{{ $document->reviewed_at->format('d/m/Y H:i') }}</span>
                                @endif
                            </td>
                            <td>
                                <a class="admin-link" href="{{ route('admin.documents.download', $document) }}">Telecharger</a>
                                <form class="inline-review-form" method="post" action="{{ route('admin.documents.update', $document) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" required>
                                        @foreach ($statuses as $value => $label)
                                            <option value="{{ $value }}" @selected($document->status === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <textarea name="admin_note" rows="2" placeholder="Note au client">{{ $document->admin_note }}</textarea>
                                    <button class="button primary" type="submit">Mettre a jour</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">Aucun document trouve.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $documents->links() }}
    </section>
</x-admin.layout>
