<x-admin.layout title="Conversation">
    <section class="admin-detail-grid">
        <article class="admin-panel">
            <div class="admin-panel-head">
                <div>
                    <h2>{{ $client->name }}</h2>
                    <span>{{ $client->email }} - {{ $client->profile?->type_client ?? 'Client' }}</span>
                </div>
                <a class="button ghost" href="{{ route('admin.messages.index') }}">Retour</a>
            </div>

            <div class="message-thread">
                @forelse ($messages as $message)
                    <article class="message-bubble {{ $message->sender_id === auth()->id() ? 'is-admin' : 'is-client' }}">
                        <span>{{ $message->sender_id === auth()->id() ? 'JCA' : $client->name }} - {{ $message->created_at->format('d/m/Y H:i') }}</span>
                        <strong>{{ $message->subject }}</strong>
                        <p>{{ $message->body }}</p>
                    </article>
                @empty
                    <article class="message-bubble is-client">
                        <span>Aucun message</span>
                        <strong>Demarrer la conversation</strong>
                        <p>Envoyez un premier message professionnel au client.</p>
                    </article>
                @endforelse
            </div>
        </article>

        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Repondre</h2>
                <span>Message interne</span>
            </div>
            @if (session('message_status'))
                <p class="form-note" data-state="success">{{ session('message_status') }}</p>
            @endif
            <form class="lead-form admin-form" method="post" action="{{ route('admin.messages.store', $client) }}">
                @csrf
                <label>Sujet<input name="subject" value="{{ old('subject', 'Réponse JCA') }}"></label>
                <label>Message<textarea name="body" rows="8" required placeholder="Écrivez une réponse claire, professionnelle et actionnable.">{{ old('body') }}</textarea></label>
                @if ($errors->any())
                    <p class="form-note" data-state="error">{{ $errors->first() }}</p>
                @endif
                <button class="button primary" type="submit">Envoyer au client</button>
            </form>
        </article>
    </section>
</x-admin.layout>
