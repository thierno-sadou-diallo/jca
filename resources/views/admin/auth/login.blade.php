<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Connexion admin | JCA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-login">
    <main class="login-panel">
        <a class="brand" href="{{ route('home') }}">
            <img class="brand-logo" src="{{ asset('images/logo_off.webp') }}" alt="Logo JCA">
            <span>
                <strong>JCA</strong>
                <small>Administration</small>
            </span>
        </a>

        <div>
            <span class="eyebrow">Accès sécurisé</span>
            <h1>Espace admin</h1>
            <p>Connectez-vous pour piloter les demandes, utilisateurs, contenus, offres et partenaires.</p>
        </div>

        <form class="lead-form admin-form" method="post" action="{{ route('admin.login.store') }}">
            @csrf
            <label>Email<input type="email" name="email" value="{{ old('email') }}" required autofocus></label>
            <label>Mot de passe
                <span class="password-field">
                    <input type="password" name="password" required data-password-input>
                    <button type="button" data-password-toggle aria-label="Afficher le mot de passe"></button>
                </span>
            </label>
            <label class="inline-choice"><input type="checkbox" name="remember" value="1"> Rester connecte</label>
            <button class="button primary" type="submit">Se connecter</button>
            @error('email')
                <p class="form-note" data-state="error">{{ $message }}</p>
            @enderror
        </form>
        <nav class="legal-auth-links" aria-label="Liens légaux">
            <a href="{{ route('legal.show', 'mentions-legales') }}">Mentions légales</a>
            <a href="{{ route('legal.show', 'politique-confidentialite') }}">Confidentialité</a>
        </nav>
    </main>
</body>
</html>
