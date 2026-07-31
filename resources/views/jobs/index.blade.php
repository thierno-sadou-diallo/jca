<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('partials.head', [
        'title' => 'Emplois internationaux | JCA',
        'description' => 'Consultez les offres d’emploi internationales publiées par JCA et trouvez des opportunités adaptées à votre profil.',
    ])
</head>
<body>
    @include('partials.header')
    <main>
        <section class="page-hero">
            <div>
                <span class="eyebrow">Talents et employeurs</span>
                <h1>Emplois internationaux</h1>
                <p>Recherchez les opportunités publiées par JCA et preparez votre candidature avec un parcours clair, professionnel et sécurisé.</p>
            </div>
        </section>

        <section class="job-board">
            <form class="job-search" method="get" action="{{ $publicRoute('jobs.index') }}">
                <label>Recherche<input name="q" value="{{ $query }}" placeholder="Metier, entreprise, competence..."></label>
                <label>Secteur<select name="sector">
                    <option value="">Tous les secteurs</option>
                    @foreach ($sectors as $item)
                        <option value="{{ $item }}" @selected($sector === $item)>{{ $item }}</option>
                    @endforeach
                </select></label>
                <label>Pays<select name="country">
                    <option value="">Tous les pays</option>
                    @foreach ($countries as $item)
                        <option value="{{ $item }}" @selected($country === $item)>{{ $item }}</option>
                    @endforeach
                </select></label>
                <button class="button primary" type="submit">Rechercher</button>
            </form>

            <div class="job-grid">
                @if (session('application_status'))
                    <article class="empty-state">
                        <h2>Candidature envoyée</h2>
                        <p>{{ session('application_status') }}</p>
                    </article>
                @endif
                @forelse ($jobs as $job)
                    <article class="job-card reveal">
                        <span>{{ $job->sector }}</span>
                        <h2>{{ $job->title }}</h2>
                        <p>{{ $job->company_name ?: 'Entreprise partenaire' }} - {{ $job->city ? $job->city.', ' : '' }}{{ $job->country }}</p>
                        <div class="job-meta">
                            <strong>{{ $job->contract_type ?: 'Contrat à définir' }}</strong>
                            <strong>{{ $job->expires_at ? 'Limite '.$job->expires_at->format('d/m/Y') : 'Ouvert' }}</strong>
                        </div>
                        <p>{{ str($job->description)->limit(170) }}</p>
                        @auth
                            <form class="lead-form admin-form compact-apply-form" method="post" action="{{ route('jobs.apply', $job) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-grid">
                                    <label>Pays<input name="country" value="{{ old('country', auth()->user()->profile?->country) }}"></label>
                                    <label>Téléphone<input name="phone" value="{{ old('phone', auth()->user()->phone) }}"></label>
                                </div>
                                <label>CV<input type="file" name="résumé" accept=".pdf,.doc,.docx" required></label>
                                <label>Message<textarea name="message" rows="3" placeholder="Resumez votre expérience et votre disponibilité.">{{ old('message') }}</textarea></label>
                                @if ($errors->any())
                                    <p class="form-note" data-state="error">{{ $errors->first() }}</p>
                                @endif
                                <button class="button primary" type="submit">Postuler</button>
                            </form>
                        @else
                            <a class="button ghost" href="{{ route('portal.register') }}">Créer un compte pour postuler</a>
                        @endauth
                    </article>
                @empty
                    <article class="empty-state">
                        <h2>Aucune offre ne correspond à votre recherche.</h2>
                        <p>Déposez votre profil pour être préqualifié lorsqu une opportunite compatible sera ouverte.</p>
                        <a class="button primary" href="{{ route('portal.register') }}">Créer un compte</a>
                    </article>
                @endforelse
            </div>

            {{ $jobs->links() }}
        </section>
    </main>
    @include('partials.footer')
    @include('partials.cookie-banner')
</body>
</html>
