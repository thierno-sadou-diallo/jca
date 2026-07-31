@csrf
<label>Nom<input name="name" value="{{ old('name', $partner->name) }}" required></label>
<div class="form-grid">
    <label>Type<input name="type" value="{{ old('type', $partner->type) }}" placeholder="Institution, ONG, Universite..."></label>
    <label>Pays<input name="country" value="{{ old('country', $partner->country) }}"></label>
</div>
<label>Site web<input type="url" name="website" value="{{ old('website', $partner->website) }}" placeholder="https://..."></label>
<label>Résumé<textarea name="summary" rows="6">{{ old('summary', $partner->summary) }}</textarea></label>
<label>Logo<input type="file" name="logo" accept="image/*"></label>
@if ($partner->logo_path)
    <p class="form-note">Logo actuel: {{ $partner->logo_path }}</p>
@endif
<label class="inline-choice">
    <input type="hidden" name="is_featured" value="0">
    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $partner->is_featured))>
    Mettre en avant ce partenaire
</label>
@if ($errors->any())
    <p class="form-note" data-state="error">{{ $errors->first() }}</p>
@endif
<button class="button primary" type="submit">{{ $submitLabel }}</button>
