<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Inscription | JCA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-login">
    <main class="register-shell">
        <section class="register-intro">
            <a class="brand" href="{{ $publicRoute('home') }}">
                <img class="brand-logo" src="{{ asset('images/logo_off.jpg') }}" alt="Logo JCA">
                <span>
                    <strong>JCA</strong>
                    <small>Espace personnel</small>
                </span>
            </a>
            <div>
                <span class="eyebrow">Compte client</span>
                <h1>Créer un compte</h1>
                <p>Un espace personnel pour déposer vos documents, envoyer vos demandes et suivre vos dossiers avec JCA.</p>
            </div>
            <div class="register-benefits">
                <span>Demandes centralisees</span>
                <span>Documents organises</span>
                <span>Suivi de dossier</span>
                <span>Rendez-vous et prochaines étapes</span>
            </div>
        </section>

        <section class="register-card">
            <form class="lead-form admin-form" method="post" action="{{ route('portal.register.store') }}" enctype="multipart/form-data">
                @csrf
                @if (in_array(request('next'), ['rendez-vous', 'dossier', 'documents'], true))
                    <input type="hidden" name="next" value="{{ request('next') }}">
                @endif
                <label>Type de client
                    <select name="type_client" required>
                        @foreach (['Particulier', 'Candidat', 'Entreprise', 'ONG', 'Institution', 'Partenaire'] as $type)
                            <option value="{{ $type }}" @selected(old('type_client') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Nom complet ou organisation<input name="name" value="{{ old('name') }}" required></label>
                <div class="form-grid">
                    <label>Email<input type="email" name="email" value="{{ old('email') }}" required></label>
                    <label>Téléphone / WhatsApp<input name="phone" value="{{ old('phone') }}"></label>
                </div>
                <div class="form-grid">
                    <label>Pays<input name="country" value="{{ old('country') }}"></label>
                    <label>Ville<input name="city" value="{{ old('city') }}"></label>
                </div>
                <label class="photo-upload">Photo de profil
                    <input type="file" name="profile_photo" accept="image/png,image/jpeg,image/webp">
                    <span>Ajoutez une photo claire pour personnaliser votre espace client.</span>
                </label>
                <label>Organisation<input name="organization_name" value="{{ old('organization_name') }}" placeholder="Entreprise, ONG, institution ou partenaire"></label>
                <div class="form-grid">
                    <label>Mot de passe
                        <span class="password-field">
                            <input type="password" name="password" required data-password-input>
                            <button type="button" data-password-toggle aria-label="Afficher le mot de passe"></button>
                        </span>
                    </label>
                    <label>Confirmer
                        <span class="password-field">
                            <input type="password" name="password_confirmation" required data-password-input>
                            <button type="button" data-password-toggle aria-label="Afficher le mot de passe"></button>
                        </span>
                    </label>
                </div>
                @if ($errors->any())
                    <p class="form-note" data-state="error">{{ $errors->first() }}</p>
                @endif
                <div class="form-actions">
                    <button class="button primary" type="submit">Créer mon espace</button>
                    <a class="button ghost" href="{{ route('portal.login', array_filter(['next' => request('next')])) }}">Connexion</a>
                </div>
                <p class="form-note">En creant un compte, vous acceptez les <a href="{{ $publicRoute('legal.show', 'conditions-utilisation') }}">conditions d’utilisation</a> et la <a href="{{ $publicRoute('legal.show', 'politique-confidentialite') }}">politique de confidentialité</a>.</p>
            </form>
        </section>
    </main>
</body>
</html>
