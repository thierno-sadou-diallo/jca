@csrf
<label>Question<input name="question" value="{{ old('question', $faq->question) }}" required></label>
<label>Reponse<textarea name="answer" rows="8" required>{{ old('answer', $faq->answer) }}</textarea></label>
<div class="form-grid">
    <label>Categorie<input name="category" value="{{ old('category', $faq->category) }}"></label>
    <label>Ordre<input type="number" min="0" max="9999" name="sort_order" value="{{ old('sort_order', $faq->sort_order ?? 0) }}" required></label>
</div>
<label class="inline-choice">
    <input type="hidden" name="is_published" value="0">
    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $faq->is_published ?? true))>
    Publier cette FAQ
</label>
@if ($errors->any())
    <p class="form-note" data-state="error">{{ $errors->first() }}</p>
@endif
<button class="button primary" type="submit">{{ $submitLabel }}</button>
