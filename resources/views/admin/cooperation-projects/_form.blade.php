@csrf
@php($indicatorRows = old('indicators', $project->indicators ?: []))
<label>Titre<input name="title" value="{{ old('title', $project->title) }}" required></label>
<div class="form-grid">
    <label>Pays<input name="country" value="{{ old('country', $project->country) }}"></label>
    <label>Secteur<input name="sector" value="{{ old('sector', $project->sector) }}" placeholder="Éducation, santé, gouvernance..."></label>
</div>
<div class="form-grid">
    <label>Date debut<input type="date" name="starts_at" value="{{ old('starts_at', $project->starts_at?->toDateString()) }}"></label>
    <label>Date fin<input type="date" name="ends_at" value="{{ old('ends_at', $project->ends_at?->toDateString()) }}"></label>
</div>
<label>Statut
    <select name="status" required>
        @foreach (['draft' => 'Brouillon', 'active' => 'Actif', 'completed' => 'Termine', 'archived' => 'Archive'] as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $project->status ?: 'draft') === $value)>{{ $label }}</option>
        @endforeach
    </select>
</label>
<label>Image de couverture<input type="file" name="image" accept="image/*"></label>
@if ($project->image_path)
    <p class="form-note">Image actuelle: {{ $project->image_path }}</p>
@endif
<div>
    <span class="form-note">Chiffres cles du projet</span>
    @for ($index = 0; $index < 3; $index++)
        <div class="form-grid">
            <label>Valeur<input name="indicators[{{ $index }}][value]" value="{{ $indicatorRows[$index]['value'] ?? '' }}" placeholder="Ex: 12"></label>
            <label>Libelle<input name="indicators[{{ $index }}][label]" value="{{ $indicatorRows[$index]['label'] ?? '' }}" placeholder="Ex: institutions mobilisees"></label>
        </div>
    @endfor
</div>
<label>Description<textarea name="description" rows="9">{{ old('description', $project->description) }}</textarea></label>
@if ($errors->any())
    <p class="form-note" data-state="error">{{ $errors->first() }}</p>
@endif
<button class="button primary" type="submit">{{ $submitLabel }}</button>
