@csrf
@isset($job->id)
    @method('PUT')
@endisset

<label>Titre de l offre<input name="title" value="{{ old('title', $job->title) }}" required></label>
<label>Entreprise<input name="company_name" value="{{ old('company_name', $job->company_name) }}"></label>
<div class="form-grid">
    <label>Pays<input name="country" value="{{ old('country', $job->country) }}" required></label>
    <label>Ville<input name="city" value="{{ old('city', $job->city) }}"></label>
</div>
<div class="form-grid">
    <label>Secteur<input name="sector" value="{{ old('sector', $job->sector) }}" required></label>
    <label>Type de contrat<input name="contract_type" value="{{ old('contract_type', $job->contract_type) }}"></label>
</div>
<label>Description<textarea name="description" rows="7" required>{{ old('description', $job->description) }}</textarea></label>
<label>Exigences<textarea name="requirements" rows="5">{{ old('requirements', $job->requirements) }}</textarea></label>
<div class="form-grid">
    <label>Statut
        <select name="status" required>
            @foreach (['draft' => 'Brouillon', 'published' => 'Publiée', 'closed' => 'Fermée'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $job->status ?: 'draft') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>
    <label>Date limite<input type="date" name="expires_at" value="{{ old('expires_at', optional($job->expires_at)->format('Y-m-d’)) }}"></label>
</div>

@if ($errors->any())
    <p class="form-note" data-state="error">{{ $errors->first() }}</p>
@endif
<button class="button primary" type="submit">Enregistrer l offre</button>
