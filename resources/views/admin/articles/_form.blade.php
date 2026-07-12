@csrf
<label>Titre<input name="title" value="{{ old('title', $article->title) }}" required></label>
<label>Type
    <select name="type" required>
        @foreach (\App\Models\Article::types() as $value => $label)
            <option value="{{ $value }}" @selected(old('type', $article->type ?: \App\Models\Article::TYPE_BLOG) === $value)>{{ $label }}</option>
        @endforeach
    </select>
</label>
<label>Resume<textarea name="excerpt" rows="3">{{ old('excerpt', $article->excerpt) }}</textarea></label>
<label>Contenu<textarea name="body" rows="12">{{ old('body', $article->body) }}</textarea></label>
<label>Statut
    <select name="status" required>
        <option value="draft" @selected(old('status', $article->status ?: 'draft') === 'draft')>Brouillon</option>
        <option value="published" @selected(old('status', $article->status) === 'published')>Publie</option>
    </select>
</label>
@if ($errors->any())
    <p class="form-note" data-state="error">{{ $errors->first() }}</p>
@endif
<button class="button primary" type="submit">{{ $submitLabel }}</button>
