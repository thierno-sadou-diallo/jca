@csrf
@php($metricRows = old('impact_metrics', $program->impact_metrics ?: []))
<label>Titre<input name="title" value="{{ old('title', $program->title) }}" required></label>
<div class="form-grid">
    <label>Pays<input name="country" value="{{ old('country', $program->country) }}"></label>
    <label>Domaine d'impact<input name="focus_area" value="{{ old('focus_area', $program->focus_area) }}" placeholder="Inclusion, sante, formation..."></label>
</div>
<label>Statut
    <select name="status" required>
        @foreach (['draft' => 'Brouillon', 'active' => 'Actif', 'completed' => 'Termine', 'archived' => 'Archive'] as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $program->status ?: 'draft') === $value)>{{ $label }}</option>
        @endforeach
    </select>
</label>
<label>Image de couverture<input type="file" name="image" accept="image/*"></label>
@if ($program->image_path)
    <p class="form-note">Image actuelle: {{ $program->image_path }}</p>
@endif
<div>
    <span class="form-note">Chiffres cles du programme</span>
    @for ($index = 0; $index < 3; $index++)
        <div class="form-grid">
            <label>Valeur<input name="impact_metrics[{{ $index }}][value]" value="{{ $metricRows[$index]['value'] ?? '' }}" placeholder="Ex: 250"></label>
            <label>Libelle<input name="impact_metrics[{{ $index }}][label]" value="{{ $metricRows[$index]['label'] ?? '' }}" placeholder="Ex: personnes accompagnees"></label>
        </div>
    @endfor
</div>
<label>Description<textarea name="description" rows="9">{{ old('description', $program->description) }}</textarea></label>
@if ($errors->any())
    <p class="form-note" data-state="error">{{ $errors->first() }}</p>
@endif
<button class="button primary" type="submit">{{ $submitLabel }}</button>
