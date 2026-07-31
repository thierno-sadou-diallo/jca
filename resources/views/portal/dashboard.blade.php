<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Espace personnel | JCA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('partials.header')

    <main class="portal-shell">
        <section class="page-hero portal-hero">
            <div class="portal-hero-grid">
                <div>
                    <span class="eyebrow">Espace personnel</span>
                    <h1>Bonjour {{ $user->name }}</h1>
                    <p>Votre espace JCA rassemble vos demandes, documents, rendez-vous et messages pour avancer avec clarté.</p>
                    <div class="portal-hero-signals" aria-label="Résumé client">
                        <span>{{ $stats['requests'] }} demande(s)</span>
                        <span>{{ $stats['documents'] }} document(s)</span>
                        <span>{{ $stats['pendingPayments'] }} paiement(s) en attente</span>
                    </div>
                </div>
                <div class="portal-user-card">
                    @if ($user->profile_photo_path)
                        <img src="{{ asset('storage/'.$user->profile_photo_path) }}" alt="Photo de {{ $user->name }}">
                    @else
                        <span>{{ str($user->name)->substr(0, 1)->upper() }}</span>
                    @endif
                    <strong>{{ $user->name }}</strong>
                    <form method="post" action="{{ route('portal.logout') }}">
                        @csrf
                        <button class="button primary" type="submit">Deconnexion</button>
                    </form>
                </div>
            </div>
        </section>

        <section class="admin-stats portal-stats">
            <article><span>Demandes</span><strong>{{ $stats['requests'] }}</strong></article>
            <article><span>Documents</span><strong>{{ $stats['documents'] }}</strong></article>
            <article><span>Notifications</span><strong>{{ $stats['unreadNotifications'] }}</strong></article>
            <article><span>Rendez-vous</span><strong>{{ $stats['appointments'] }}</strong></article>
            <article><span>Paiements</span><strong>{{ $stats['pendingPayments'] }}</strong></article>
        </section>

        <section class="portal-action-board" aria-label="Actions recommandées">
            <a href="#portal-request">
                <span>01</span>
                <strong>Déposer mon dossier</strong>
                <p>Expliquez votre objectif et joignez les premières pièces utiles.</p>
            </a>
            <a href="#portal-appointment">
                <span>02</span>
                <strong>Prendre rendez-vous</strong>
                <p>Choisissez un créneau pour clarifier la stratégie avec JCA.</p>
            </a>
            <a href="#portal-documents">
                <span>03</span>
                <strong>Compléter mes documents</strong>
                <p>Ajoutez CV, passeport, diplômes ou preuves pour accélérer l’analyse.</p>
            </a>
        </section>

        <section class="journey-panel">
            <div class="journey-copy">
                <span class="eyebrow">Feuille de route intelligente</span>
                <h2>Votre prochain meilleur mouvement.</h2>
                <p>JCA transforme votre avancement en parcours clair : ce qui est fait, ce qui bloque, et ce qui vous rapproche du résultat.</p>
                <div class="portal-media-strip" aria-label="Aperçu visuel du parcours JCA">
                    <img src="{{ asset('images/jca-immigration.webp') }}" alt="Dossier immigration" loading="lazy">
                    <img src="{{ asset('images/jca-recruitment.webp') }}" alt="Mobilité professionnelle" loading="lazy">
                    <img src="{{ asset('images/jca-cooperation.webp') }}" alt="Projet international" loading="lazy">
                </div>
            </div>
            <div class="journey-progress">
                <div class="journey-steps">
                    @foreach ($journeySteps as $step)
                        <article @class(['is-done' => $step['done']])>
                            <span>{{ $step['done'] ? 'Fait' : 'A faire' }}</span>
                            <strong>{{ $step['title'] }}</strong>
                            <p>{{ $step['text'] }}</p>
                        </article>
                    @endforeach
                </div>

                <aside class="journey-motivation">
                    <span>Votre dossier avance ici</span>
                    <strong>Restez actif, JCA suit chaque pièce ajoutée.</strong>
                    <p>Un profil complet, des documents clairs et un message précis permettent à l’équipe de mieux vous orienter.</p>
                    <div class="journey-motivation-actions">
                        <a href="#portal-profile">Compléter mon profil</a>
                        <a href="#portal-documents">Ajouter un document</a>
                    </div>
                </aside>
            </div>
        </section>

        <section class="portal-workspace">
            <div class="section-heading">
                <span class="eyebrow">Modules disponibles</span>
                <h2>Votre espace de suivi international.</h2>
                <p>Déposez vos documents, suivez vos dossiers et conservez vos prochaines étapes au même endroit.</p>
            </div>

            <div class="portal-grid">
                <article class="admin-panel portal-priority">
                    <div class="admin-panel-head">
                        <h2>Notifications</h2>
                        <span>{{ $stats['unreadNotifications'] }} non lue(s)</span>
                    </div>
                    @if (session('notification_status'))
                        <p class="form-note" data-state="success">{{ session('notification_status') }}</p>
                    @endif
                    @if ($notifications->isNotEmpty())
                        <form method="post" action="{{ route('portal.notifications.read') }}" class="form-actions">
                            @csrf
                            <button class="button ghost" type="submit">Tout marquer comme lu</button>
                        </form>
                    @endif
                    <div class="admin-list">
                        @forelse ($notifications as $notification)
                            <div>
                                <strong>{{ $notification->data['title'] ?? 'Notification JCA' }}</strong>
                                <span>{{ $notification->read_at ? 'Lue' : 'Non lue' }} - {{ $notification->created_at->format('d/m/Y H:i') }}</span>
                                <p>{{ $notification->data['message'] ?? '' }}</p>
                            </div>
                        @empty
                            <div><strong>Aucune notification</strong><span>Les mises à jour importantes apparaîtront ici.</span></div>
                        @endforelse
                    </div>
                </article>

                <article class="admin-panel portal-priority" id="portal-profile">
                    <div class="admin-panel-head">
                        <h2>Profil client</h2>
                        <span>{{ $profile->type_client }}</span>
                    </div>
                    @if (session('profile_status'))
                        <p class="form-note" data-state="success">{{ session('profile_status') }}</p>
                    @endif
                    <form class="lead-form admin-form" method="post" action="{{ route('portal.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <label>Nom complet ou organisation
                            <input name="name" value="{{ old('name', $user->name) }}" required>
                        </label>
                        <div class="form-grid">
                            <label>Type de client
                                <select name="type_client" required>
                                    @foreach ($clientTypes as $type)
                                        <option value="{{ $type }}" @selected(old('type_client', $profile->type_client) === $type)>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label>Langue préférée
                                <select name="preferred_language" required>
                                    <option value="fr" @selected(old('preferred_language', $profile->preferred_language) === 'fr')>FR</option>
                                    <option value="en" @selected(old('preferred_language', $profile->preferred_language) === 'en')>EN</option>
                                </select>
                            </label>
                        </div>
                        <div class="form-grid">
                            <label>Téléphone / WhatsApp
                                <input name="phone" value="{{ old('phone', $user->phone) }}">
                            </label>
                            <label>Organisation
                                <input name="organization_name" value="{{ old('organization_name', $profile->organization_name) }}" placeholder="Entreprise, ONG, institution...">
                            </label>
                        </div>
                        <div class="form-grid">
                            <label>Pays
                                <input name="country" value="{{ old('country', $profile->country) }}">
                            </label>
                            <label>Ville
                                <input name="city" value="{{ old('city', $profile->city) }}">
                            </label>
                        </div>
                        <label class="photo-upload">Photo de profil
                            <input type="file" name="profile_photo" accept="image/png,image/jpeg,image/webp">
                            <span>Remplacez votre photo si vous souhaitez personnaliser davantage votre espace.</span>
                        </label>
                        @if ($errors->any())
                            <p class="form-note" data-state="error">{{ $errors->first() }}</p>
                        @endif
                        <button class="button primary" type="submit">Mettre à jour mon profil</button>
                    </form>
                </article>

                <article class="admin-panel portal-priority" id="portal-appointment">
                    <div class="admin-panel-head">
                        <h2>Nouveau rendez-vous</h2>
                        <span>Dates ouvertes par JCA</span>
                    </div>
                    <div class="portal-card-nudge">
                        <strong>Un rendez-vous transforme votre projet en plan clair.</strong>
                        <p>Choisissez un créneau si vous souhaitez valider les étapes, les pièces et les priorités.</p>
                    </div>
                    @if (session('appointment_status'))
                        <p class="form-note" data-state="success">{{ session('appointment_status') }}</p>
                    @endif
                    @if ($appointmentSlotOptions->isNotEmpty())
                        <form class="lead-form admin-form appointment-picker" method="post" action="{{ route('portal.appointments.store') }}" data-appointment-picker>
                            @csrf
                            <div class="appointment-calendar appointment-calendar-client" aria-label="Calendrier des rendez-vous disponibles">
                                @foreach ($appointmentCalendars as $calendar)
                                    <section class="compact-calendar">
                                        <h3>{{ $calendar['label'] }}</h3>
                                        <div class="calendar-weekdays" aria-hidden="true">
                                            <span>Lun</span>
                                            <span>Mar</span>
                                            <span>Mer</span>
                                            <span>Jeu</span>
                                            <span>Ven</span>
                                            <span>Sam</span>
                                            <span>Dim</span>
                                        </div>
                                        <div class="calendar-grid">
                                            @foreach ($calendar['days'] as $day)
                                                @if ($day['blank'])
                                                    <span class="calendar-empty" aria-hidden="true"></span>
                                                @else
                                                    <div @class([
                                                        'calendar-day calendar-slot-day',
                                                        'is-disabled' => $day['isPast'] || $day['slots']->isEmpty(),
                                                        'is-active' => $day['slots']->isNotEmpty(),
                                                        'is-today' => $day['isToday'],
                                                        'is-weekend' => $day['isWeekend'],
                                                    ])>
                                                        <strong>{{ $day['number'] }}</strong>
                                                        <span class="calendar-dot-row" aria-hidden="true">
                                                            @forelse ($day['slots']->take(3) as $slot)
                                                                <i></i>
                                                            @empty
                                                                <i></i>
                                                            @endforelse
                                                        </span>
                                                        @if ($day['slots']->isNotEmpty())
                                                            <div class="calendar-slot-options">
                                                                @foreach ($day['slots'] as $slot)
                                                                    <label>
                                                                        <input type="radio" name="slot_id" value="{{ $slot['id'] }}" required>
                                                                        <span>{{ $slot['time'] }}</span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </section>
                                @endforeach
                            </div>
                            <button class="button primary" type="submit">Confirmer le rendez-vous</button>
                        </form>
                    @else
                        <p class="form-note">Aucune disponibilité ouverte pour cette semaine ou la semaine prochaine.</p>
                    @endif
                    <div class="admin-list">
                        @forelse ($appointments as $appointment)
                            <div>
                                <strong>{{ $appointment->topic }}</strong>
                                <span>{{ $appointment->starts_at?->format('d/m/Y H:i') ?: 'Date à confirmer' }} - {{ \App\Models\Appointment::statuses()[$appointment->status] ?? $appointment->status }}</span>
                                @if ($appointment->notes)
                                    <p>{{ $appointment->notes }}</p>
                                @endif
                            </div>
                        @empty
                            <div><strong>Aucun rendez-vous</strong><span>Planifiez une consultation pour recevoir une feuille de route.</span></div>
                        @endforelse
                    </div>
                </article>

                <article class="admin-panel" id="portal-request">
                    <div class="admin-panel-head">
                        <h2>Nouvelle demande</h2>
                        <span>Immigration, emploi, partenariat ou consultation</span>
                    </div>
                    <div class="portal-card-nudge">
                        <strong>Plus votre demande est précise, plus l’analyse est rapide.</strong>
                        <p>Indiquez votre objectif, votre pays cible, vos délais et les documents déjà disponibles.</p>
                    </div>
                    @if (session('lead_success'))
                        <p class="form-note" data-state="success">{{ session('lead_success') }}</p>
                    @endif
                    <form class="lead-form admin-form" method="post" action="{{ route('lead-requests.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <input type="hidden" name="phone" value="{{ $user->phone }}">
                        <input type="hidden" name="source" value="portal">
                        <input type="hidden" name="page_slug" value="espace">
                        <label>Motif
                            <select name="topic" required>
                                <option>Immigration</option>
                                <option>Recrutement international</option>
                                <option>Coopération internationale</option>
                                <option>Partenariat</option>
                                <option>Consultation stratégique</option>
                            </select>
                        </label>
                        <label>Canal préféré
                            <select name="preferred_channel">
                                <option>Email</option>
                                <option>WhatsApp</option>
                                <option>Téléphone</option>
                            </select>
                        </label>
                        <label>Documents
                            <input type="file" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        </label>
                        <label>Message<textarea name="message" rows="5" required placeholder="Expliquez votre situation, votre objectif et les délais importants."></textarea></label>
                        <button class="button primary" type="submit">Envoyer ma demande</button>
                    </form>
                </article>

                <article class="admin-panel portal-priority">
                    <div class="admin-panel-head">
                        <h2>Mes demandes</h2>
                        <span>Réponses et suivi</span>
                    </div>
                    <div class="admin-list">
                        @forelse ($leads as $lead)
                            @php($payload = json_decode($lead->payload ?? '[]', true) ?: [])
                            <div>
                                <strong>{{ $lead->topic }} - {{ $lead->status }}</strong>
                                <span>{{ $lead->created_at }}{{ isset($payload['response_message']) ? ' - Réponse disponible' : '' }}</span>
                                @if (! empty($payload['response_message']))
                                    <p>{{ $payload['response_message'] }}</p>
                                @endif
                            </div>
                        @empty
                            <div><strong>Aucune demande</strong><span>Envoyez une première demande depuis votre espace.</span></div>
                        @endforelse
                    </div>
                </article>

                <article class="admin-panel portal-priority">
                    <div class="admin-panel-head">
                        <h2>Messagerie JCA</h2>
                        <span>Echanges confidentiels</span>
                    </div>
                    @if (session('message_status'))
                        <p class="form-note" data-state="success">{{ session('message_status') }}</p>
                    @endif
                    <form class="lead-form admin-form" method="post" action="{{ route('portal.messages.store') }}">
                        @csrf
                        <label>Sujet<input name="subject" value="{{ old('subject') }}" placeholder="Question, document, suivi de dossier..."></label>
                        <label>Message<textarea name="body" rows="5" required placeholder="Écrivez votre message à l’équipe JCA.">{{ old('body') }}</textarea></label>
                        <button class="button primary" type="submit">Envoyer un message</button>
                    </form>
                    <div class="message-thread compact-thread">
                        @forelse ($messages as $message)
                            <article class="message-bubble {{ $message->sender_id === $user->id ? 'is-client' : 'is-admin' }}">
                                <span>{{ $message->sender_id === $user->id ? 'Vous' : 'JCA' }} - {{ $message->created_at->format('d/m/Y H:i') }}</span>
                                <strong>{{ $message->subject }}</strong>
                                <p>{{ $message->body }}</p>
                            </article>
                        @empty
                            <article class="message-bubble is-admin">
                                <span>Messagerie</span>
                                <strong>Aucun message</strong>
                                <p>Envoyez une question ou une précision à l’équipe JCA.</p>
                            </article>
                        @endforelse
                    </div>
                </article>

                <article class="admin-panel" id="portal-documents">
                    <div class="admin-panel-head">
                        <h2>Déposer un document</h2>
                        <span>PDF, image ou Word - 8 Mo max</span>
                    </div>
                    <div class="portal-card-nudge">
                        <strong>Chaque document ajoute de la force à votre dossier.</strong>
                        <p>Commencez par les pièces principales, puis complétez progressivement selon les demandes de JCA.</p>
                    </div>
                    @if (session('document_status'))
                        <p class="form-note" data-state="success">{{ session('document_status') }}</p>
                    @endif
                    <form class="lead-form admin-form" method="post" action="{{ route('portal.documents.store') }}" enctype="multipart/form-data">
                        @csrf
                        <label>Titre<input name="title" placeholder="Passeport, CV, diplôme..." required></label>
                        <label>Type
                            <select name="type" required>
                                <option>Passeport</option>
                                <option>CV</option>
                                <option>Diplôme</option>
                                <option>Preuve d’expérience</option>
                                <option>Document financier</option>
                                <option>Autre</option>
                            </select>
                        </label>
                        <label>Fichier<input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required></label>
                        @if ($errors->any())
                            <p class="form-note" data-state="error">{{ $errors->first() }}</p>
                        @endif
                        <button class="button primary" type="submit">Déposer le document</button>
                    </form>
                </article>

                <article class="admin-panel">
                    <div class="admin-panel-head">
                        <h2>Donner mon avis</h2>
                        <span>Retour d’expérience</span>
                    </div>
                    @if (session('review_status'))
                        <p class="form-note" data-state="success">{{ session('review_status') }}</p>
                    @endif
                    <form class="lead-form admin-form" method="post" action="{{ route('portal.reviews.store') }}">
                        @csrf
                        <label>Votre avis<textarea name="quote" rows="5" required placeholder="Partagez votre expérience avec JCA."></textarea></label>
                        <button class="button primary" type="submit">Envoyer mon avis</button>
                    </form>
                    <div class="admin-list">
                        @forelse ($reviews as $review)
                            <div>
                                <strong>{{ $review->status }}</strong>
                                <span>{{ $review->created_at }}</span>
                                <p>{{ $review->quote }}</p>
                                @if ($review->admin_response)
                                    <p><strong>Réponse JCA:</strong> {{ $review->admin_response }}</p>
                                @endif
                            </div>
                        @empty
                            <div><strong>Aucun avis</strong><span>Votre retour aide JCA à améliorer l’accompagnement.</span></div>
                        @endforelse
                    </div>
                </article>

                <article class="admin-panel">
                    <div class="admin-panel-head">
                        <h2>Mes documents</h2>
                        <span>{{ $stats['documents'] }} fichier(s)</span>
                    </div>
                    <div class="admin-list">
                        @forelse ($documents as $document)
                            <div>
                                <strong>{{ $document->title }}</strong>
                                <span>{{ $document->type }} - {{ \App\Models\Document::statuses()[$document->status] ?? $document->status }} - {{ $document->created_at->format('d/m/Y') }}</span>
                                @if ($document->admin_note)
                                    <p>{{ $document->admin_note }}</p>
                                @endif
                            </div>
                        @empty
                            <div><strong>Aucun document</strong><span>Déposez vos pièces pour accélérer l’analyse.</span></div>
                        @endforelse
                    </div>
                </article>

                <article class="admin-panel">
                    <div class="admin-panel-head">
                        <h2>Mes candidatures</h2>
                        <span>{{ $stats['applications'] }} dossier(s)</span>
                    </div>
                    <div class="admin-list">
                        @forelse ($applications as $application)
                            <div>
                                <strong>{{ $application->jobPosting?->title ?? 'Offre supprimee' }}</strong>
                                <span>{{ \App\Models\JobApplication::statuses()[$application->status] ?? $application->status }} - {{ $application->created_at->format('d/m/Y') }}</span>
                                @if ($application->admin_note)
                                    <p>{{ $application->admin_note }}</p>
                                @endif
                            </div>
                        @empty
                            <div><strong>Aucune candidature</strong><span>Consultez les offres et postulez avec votre CV.</span></div>
                        @endforelse
                    </div>
                </article>

                <article class="admin-panel portal-priority">
                    <div class="admin-panel-head">
                        <h2>Facturation</h2>
                        <span>{{ $stats['payments'] }} paiement(s)</span>
                    </div>
                    <div class="admin-list">
                        @forelse ($payments as $payment)
                            <div>
                                <strong>{{ $payment->reference }}</strong>
                                <span>{{ number_format((float) $payment->amount, 2, ',', ' ') }} {{ $payment->currency }} - {{ \App\Models\Payment::statuses()[$payment->status] ?? $payment->status }}</span>
                                @if (! empty($payment->payload['note']))
                                    <p>{{ $payment->payload['note'] }}</p>
                                @endif
                                @if (! empty($payment->payload['payment_url']) && $payment->status === \App\Models\Payment::STATUS_PENDING)
                                    <a class="admin-link" href="{{ $payment->payload['payment_url'] }}" target="_blank" rel="noopener">Ouvrir le lien de paiement</a>
                                @endif
                            </div>
                        @empty
                            <div><strong>Aucun paiement</strong><span>Les factures et frais de consultation apparaîtront ici.</span></div>
                        @endforelse
                    </div>
                </article>

                <article class="admin-panel">
                    <div class="admin-panel-head">
                        <h2>Suivi immigration</h2>
                        <span>Dossiers actifs</span>
                    </div>
                    <div class="admin-list">
                        @forelse ($immigrationCases as $case)
                            @php($latestCaseHistory = $case->histories->sortByDesc('id')->first())
                            <div>
                                <strong>{{ $case->reference }}</strong>
                                <span>{{ $case->program_type }} - {{ \App\Models\ImmigrationCase::statuses()[$case->status] ?? $case->status }}</span>
                                @if ($latestCaseHistory)
                                    <p>{{ $latestCaseHistory->note ?: 'Derniere mise à jour du dossier.' }}</p>
                                @endif
                            </div>
                        @empty
                            <div><strong>Aucun dossier actif</strong><span>Une demande convertie en dossier apparaîtra ici.</span></div>
                        @endforelse
                    </div>
                </article>

            </div>

        </section>
    </main>

    @include('partials.footer')
</body>
</html>
