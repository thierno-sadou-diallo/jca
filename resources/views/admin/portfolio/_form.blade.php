<form class="admin-form" method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="form-grid">
        <label>Titre
            <input type="text" name="title" value="{{ old('title', $item->title) }}" required>
        </label>
        <label>Type
            <select name="type" required>
                @foreach (['evenement' => 'Evenement', 'forum' => 'Forum', 'relation-presse' => 'Relation presse', 'mission' => 'Mission', 'conference' => 'Conference'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('type', $item->type ?: 'evenement') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <label>Lieu
            <input type="text" name="location" value="{{ old('location', $item->location) }}">
        </label>
        <label>Date
            <input type="date" name="event_date" value="{{ old('event_date', optional($item->event_date)->format('Y-m-d')) }}">
        </label>
        <label>Ordre
            <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $item->sort_order ?? 0) }}">
        </label>
        <label>Image
            <input type="file" name="image" accept="image/*">
        </label>
    </div>

    <label>Resume
        <textarea name="excerpt" rows="3">{{ old('excerpt', $item->excerpt) }}</textarea>
    </label>

    <label>Contenu
        <textarea name="body" rows="8">{{ old('body', $item->body) }}</textarea>
    </label>

    <label class="admin-check">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $item->is_published))>
        Publier sur le site
    </label>

    @if ($errors->any())
        <div class="admin-alert danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <button class="admin-btn" type="submit">Enregistrer</button>
</form>
