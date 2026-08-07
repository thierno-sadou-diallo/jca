<x-admin.layout title="Messages">
    <section class="admin-grid">
        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Conversations</h2>
                <span>{{ $clients->count() }} échange(s)</span>
            </div>
            <div class="admin-list">
                @forelse ($clients as $client)
                    <div>
                        <strong>{{ $client->name }}</strong>
                        <span>
                            {{ $client->profile?->type_client ?? 'Client' }}
                            @if ($client->unread_messages_count)
                                - {{ $client->unread_messages_count }} non lu(s)
                            @endif
                        </span>
                        @if ($client->latest_message)
                            <p>{{ str($client->latest_message->body)->limit(120) }}</p>
                        @endif
                        <a class="admin-link" href="{{ route('admin.messages.show', $client) }}">Ouvrir</a>
                    </div>
                @empty
                    <div><strong>Aucune conversation</strong><span>Les messages clients apparaîtront ici.</span></div>
                @endforelse
            </div>
        </article>

        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Nouveau message</h2>
                <span>Démarrer un échange</span>
            </div>
            <div class="admin-list">
                @forelse ($allClients as $client)
                    <div>
                        <strong>{{ $client->name }}</strong>
                        <span>{{ $client->email }}</span>
                        <a class="admin-link" href="{{ route('admin.messages.show', $client) }}">Écrire</a>
                    </div>
                @empty
                    <div><strong>Aucun client actif</strong><span>Les comptes clients actifs seront listés ici.</span></div>
                @endforelse
            </div>
        </article>
    </section>
</x-admin.layout>
