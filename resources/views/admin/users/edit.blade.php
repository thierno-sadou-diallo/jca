<x-admin.layout title="Modifier utilisateur">
    <section class="admin-detail-grid">
        <article class="admin-panel">
            <div class="admin-panel-head">
                <div>
                    <h2>{{ $managedUser->name }}</h2>
                    <span>{{ $managedUser->email }}</span>
                </div>
                @if ($managedUser->role === 'client')
                    <a class="button ghost" href="{{ route('admin.clients.show', $managedUser) }}">Fiche client</a>
                @endif
            </div>
            <div class="admin-list">
                <div><strong>Role actuel</strong><span>{{ $managedUser->role }}</span></div>
                <div><strong>Statut</strong><span>{{ $managedUser->status }}</span></div>
                <div><strong>Type client</strong><span>{{ $managedUser->profile?->type_client ?: 'Non applicable' }}</span></div>
                <div><strong>Creation</strong><span>{{ $managedUser->created_at->format('d/m/Y H:i') }}</span></div>
            </div>
        </article>

        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Paramètres du compte</h2>
                <span>Accès et statut</span>
            </div>
            @if (session('status'))
                <p class="form-note" data-state="success">{{ session('status') }}</p>
            @endif
            <form class="lead-form admin-form" method="post" action="{{ route('admin.users.update', $managedUser) }}">
                @csrf
                @method('PATCH')
                <label>Nom<input name="name" value="{{ old('name', $managedUser->name) }}" required></label>
                <label>Téléphone<input name="phone" value="{{ old('phone', $managedUser->phone) }}"></label>
                <div class="form-grid">
                    <label>Role
                        <select name="role" required>
                            <option value="admin" @selected($managedUser->role === 'admin')>Administrateur</option>
                            <option value="client" @selected($managedUser->role === 'client')>Client</option>
                        </select>
                    </label>
                    <label>Statut
                        <select name="status" required>
                            @foreach (['active' => 'Actif', 'inactive' => 'Inactif', 'suspended' => 'Suspendu'] as $value => $label)
                                <option value="{{ $value }}" @selected($managedUser->status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                @if ($errors->any())
                    <p class="form-note" data-state="error">{{ $errors->first() }}</p>
                @endif
                <button class="button primary" type="submit">Enregistrer</button>
            </form>
        </article>
    </section>
</x-admin.layout>
