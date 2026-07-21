<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('partials.head', [
        'title' => 'Emplois internationaux | JCA',
        'description' => 'Consultez les offres d emploi internationales publiees par JCA et trouvez des opportunites adaptees a votre profil.',
    ])
</head>
<body>
    @include('partials.header')
    <main>
        <section class="page-hero">
            <div>
                <span class="eyebrow">Talents et employeurs</span>
                <h1>Emplois internationaux</h1>
                <p>Recherchez les opportunites publiees par JCA et preparez votre candidature avec un parcours clair, professionnel et securise.</p>
            </div>
        </section>

        <section class="job-board">
            <form class="job-search" method="get" action="{{ route('jobs.index') }}">
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
                        <h2>Candidature envoyee</h2>
                        <p>{{ session('application_status') }}</p>
                    </article>
                @endif
                @forelse ($jobs as $job)
                    <article class="job-card reveal">
                        <span>{{ $job->sector }}</span>
                        <h2>{{ $job->title }}</h2>
                        <p>{{ $job->company_name ?: 'Entreprise partenaire' }} - {{ $job->city ? $job->city.', ' : '' }}{{ $job->country }}</p>
                        <div class="job-meta">
                            <strong>{{ $job->contract_type ?: 'Contrat a definir' }}</strong>
                            <strong>{{ $job->expires_at ? 'Limite '.$job->expires_at->format('d/m/Y') : 'Ouvert' }}</strong>
                        </div>
                        <p>{{ str($job->description)->limit(170) }}</p>
                        @auth
                            <form class="lead-form admin-form compact-apply-form" method="post" action="{{ route('jobs.apply', $job) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-grid">
                                    <label>Pays<input name="country" value="{{ old('country', auth()->user()->profile?->country) }}"></label>
                                    <label>Telephone<input name="phone" value="{{ old('phone', auth()->user()->phone) }}"></label>
                                </div>
                                <label>CV<input type="file" name="resume" accept=".pdf,.doc,.docx" required></label>
                                <label>Message<textarea name="message" rows="3" placeholder="Resumez votre experience et votre disponibilite.">{{ old('message') }}</textarea></label>
                                @if ($errors->any())
                                    <p class="form-note" data-state="error">{{ $errors->first() }}</p>
                                @endif
                                <button class="button primary" type="submit">Postuler</button>
                            </form>
                        @else
                            <a class="button ghost" href="{{ route('portal.register') }}">Creer un compte pour postuler</a>
                        @endauth
                    </article>
                @empty
                    <article class="empty-state">
                        <h2>Aucune offre ne correspond a votre recherche.</h2>
                        <p>Deposez votre profil pour etre prequalifie lorsqu une opportunite compatible sera ouverte.</p>
                        <a class="button primary" href="{{ route('portal.register') }}">Creer un compte</a>
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
