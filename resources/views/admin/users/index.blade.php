<x-admin.layout title="Utilisateurs">
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <h2>Gestion des utilisateurs</h2>
                <span>Comptes administrateurs et clients</span>
            </div>
        </div>
        <form class="admin-filter wide-filter" method="get" action="{{ route('admin.users.index') }}">
            <input name="q" value="{{ $query }}" placeholder="Nom, email, telephone">
            <select name="role">
                <option value="">Tous les roles</option>
                <option value="admin" @selected($role === 'admin')>Administrateur</option>
                <option value="client" @selected($role === 'client')>Client</option>
            </select>
            <select name="status">
                <option value="">Tous les statuts</option>
                @foreach (['active' => 'Actif', 'inactive' => 'Inactif', 'suspended' => 'Suspendu'] as $value => $label)
                    <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="button primary" type="submit">Filtrer</button>
        </form>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Utilisateur</th><th>Role</th><th>Type client</th><th>Statut</th><th>Date</th><th></th></tr></thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td><strong>{{ $user->name }}</strong><span>{{ $user->email }} {{ $user->phone ? '- '.$user->phone : '' }}</span></td>
                            <td>{{ $user->role }}</td>
                            <td>{{ $user->profile?->type_client ?: 'Non applicable' }}</td>
                            <td><mark>{{ $user->status }}</mark></td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td><a class="admin-link" href="{{ route('admin.users.edit', $user) }}">Modifier</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6">Aucun utilisateur trouve.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
    </section>
</x-admin.layout>
