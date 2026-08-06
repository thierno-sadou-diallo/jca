<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>{{ $title ?? 'Administration' }} | JCA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-shell">
    <aside class="admin-sidebar">
        <a class="brand admin-brand" href="{{ route('admin.dashboard') }}">
            <img class="brand-logo" src="{{ asset('images/logo_off.webp') }}" alt="Logo JCA">
            <span>
                <strong>Administration</strong>
                <small>Back-office</small>
            </span>
        </a>
        <nav class="admin-menu" aria-label="Navigation admin">
            <a @class(['is-active' => request()->routeIs('admin.dashboard')]) href="{{ route('admin.dashboard') }}">Tableau de bord</a>
            <a @class(['is-active' => request()->routeIs('admin.users.*')]) href="{{ route('admin.users.index') }}">Utilisateurs</a>
            <a @class(['is-active' => request()->routeIs('admin.clients.*')]) href="{{ route('admin.clients.index') }}">Clients</a>
            <a @class(['is-active' => request()->routeIs('admin.leads.*')]) href="{{ route('admin.leads.index') }}">Demandes</a>
            <a @class(['is-active' => request()->routeIs('admin.documents.*')]) href="{{ route('admin.documents.index') }}">Documents</a>
            <a @class(['is-active' => request()->routeIs('admin.immigration-cases.*')]) href="{{ route('admin.immigration-cases.index') }}">Dossiers</a>
            <a @class(['is-active' => request()->routeIs('admin.jobs.*')]) href="{{ route('admin.jobs.index') }}">Emplois</a>
            <a @class(['is-active' => request()->routeIs('admin.applications.*')]) href="{{ route('admin.applications.index') }}">Candidatures</a>
            <a @class(['is-active' => request()->routeIs('admin.articles.*')]) href="{{ route('admin.articles.index') }}">Articles</a>
            <a @class(['is-active' => request()->routeIs('admin.portfolio.*')]) href="{{ route('admin.portfolio.index') }}">Portfolio</a>
            <a @class(['is-active' => request()->routeIs('admin.faqs.*')]) href="{{ route('admin.faqs.index') }}">FAQ</a>
            <a @class(['is-active' => request()->routeIs('admin.cooperation-projects.*')]) href="{{ route('admin.cooperation-projects.index') }}">Coopération</a>
            <a @class(['is-active' => request()->routeIs('admin.humanitarian-programs.*')]) href="{{ route('admin.humanitarian-programs.index') }}">Humanitaire</a>
            <a @class(['is-active' => request()->routeIs('admin.partners.*')]) href="{{ route('admin.partners.index') }}">Partenaires</a>
            <a @class(['is-active' => request()->routeIs('admin.appointments.*')]) href="{{ route('admin.appointments.index') }}">Rendez-vous</a>
            <a @class(['is-active' => request()->routeIs('admin.payments.*')]) href="{{ route('admin.payments.index') }}">Paiements</a>
            <a @class(['is-active' => request()->routeIs('admin.availability.*')]) href="{{ route('admin.availability.index') }}">Disponibilités</a>
            <a @class(['is-active' => request()->routeIs('admin.messages.*')]) href="{{ route('admin.messages.index') }}">Messages</a>
            <a @class(['is-active' => request()->routeIs('admin.reviews.*')]) href="{{ route('admin.reviews.index') }}">Avis</a>
            <a @class(['is-active' => request()->routeIs('admin.reports.*')]) href="{{ route('admin.reports.index') }}">Statistiques</a>
            <a @class(['is-active' => request()->routeIs('admin.settings.*')]) href="{{ route('admin.settings.edit') }}">Paramètres</a>
            <a href="{{ route('home') }}">Voir le site</a>
        </nav>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <div>
                <span class="eyebrow">JCA Admin</span>
                <h1>{{ $title ?? 'Tableau de bord' }}</h1>
                <p>Bonjour {{ auth()->user()?->name ?? 'Admin' }}, pilotez les demandes, dossiers et contenus depuis un espace clair et réactif.</p>
                <p class="security-note">Sécurité équipe: utilisez un mot de passe fort, un gestionnaire de mots de passe et activez la 2FA sur les comptes administrateurs dès que le fournisseur d’identité ou le module Laravel le permet.</p>
            </div>
            <form method="post" action="{{ route('admin.logout') }}">
                @csrf
                <button class="button ghost" type="submit">Déconnexion</button>
            </form>
        </header>

        {{ $slot }}
    </div>
</body>
</html>
