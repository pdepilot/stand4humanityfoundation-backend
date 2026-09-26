@if ($errors->any())
    <div class="settings-alert" role="alert">{{ $errors->first() }}</div>
@endif

@php
    /** @var \App\Models\NewsArticle|null $newsArticle */
    /** @var \App\Models\NewsArticle|null $article */
    $article = isset($newsArticle) ? $newsArticle : null;
    $tagValue = old('tags');
    if ($tagValue === null && $article) {
        $tagValue = implode(', ', $article->tagList());
    }
@endphp

<p class="news-form-hint">Write for readers first. Google AdSense reviews public pages for original, useful content, clear navigation, and a real privacy policy.</p>

<div class="settings-grid">
    <div class="settings-field">
        <label for="title">Title</label>
        <input id="title" name="title" type="text" value="{{ old('title', $article?->title) }}" required data-news-title>
        <div class="news-count-line"><span>Clear, specific headline</span><span data-news-title-count>0</span></div>
    </div>
    <div class="settings-field">
        <label for="slug">URL slug</label>
        <input id="slug" name="slug" type="text" value="{{ old('slug', $article?->slug) }}" placeholder="auto from title" required>
    </div>
</div>

<div class="settings-field">
    <label for="excerpt">Excerpt</label>
    <textarea id="excerpt" name="excerpt" rows="3" required data-news-excerpt>{{ old('excerpt', $article?->excerpt) }}</textarea>
    <div class="news-count-line"><span>Shown on the journal listing</span><span data-news-excerpt-count>0</span></div>
</div>

<div class="settings-field">
    <label for="body">Body (Markdown)</label>
    <textarea id="body" name="body" rows="16" required data-news-body>{{ old('body', $article?->body) }}</textarea>
    <div class="news-count-line"><span>Aim for 180+ words</span><span data-news-body-count>0 words</span></div>
</div>

<div class="settings-grid">
    <div class="settings-field">
        <label for="image">Cover image</label>
        @if ($article)
            <div class="news-current-cover">
                <img src="{{ $article->imageUrl() }}" alt="{{ $article->image_alt }}">
                <span>{{ $article->hasUploadedImage() ? 'Current upload' : 'Programme library image' }}</span>
            </div>
        @endif
        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/gif" @required(! $article)>
        <div class="news-count-line">
            <span>{{ $article ? 'Leave empty to keep the current cover' : 'JPG, PNG, WEBP, or GIF · max 5 MB' }}</span>
        </div>
    </div>
    <div class="settings-field">
        <label for="image_alt">Image description</label>
        <input id="image_alt" name="image_alt" type="text" value="{{ old('image_alt', $article?->image_alt) }}" required>
    </div>
</div>

<div class="settings-grid">
    <div class="settings-field">
        <label for="author">Author</label>
        <input id="author" name="author" type="text" value="{{ old('author', $article?->author ?? 'Foundation desk') }}">
    </div>
    <div class="settings-field">
        <label for="reading_minutes">Reading minutes</label>
        <input id="reading_minutes" name="reading_minutes" type="number" min="1" max="60" value="{{ old('reading_minutes', $article?->reading_minutes ?? 4) }}" required>
    </div>
</div>

<div class="settings-grid">
    <div class="settings-field">
        <label for="tags">Tags</label>
        <input id="tags" name="tags" type="text" value="{{ $tagValue }}" placeholder="Child Protection, Owerri">
    </div>
    <div class="settings-field">
        <label for="published_at">Publish date</label>
        <input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at', $article?->published_at?->timezone(config('app.timezone'))->format('Y-m-d\TH:i') ?? now()->timezone(config('app.timezone'))->format('Y-m-d\TH:i')) }}" required>
    </div>
</div>

<div class="settings-field">
    <label for="search_keywords">Search keywords</label>
    <input id="search_keywords" name="search_keywords" type="text" value="{{ old('search_keywords', $article?->search_keywords) }}" placeholder="child protection rescue owerri">
</div>

<div class="news-check-row">
    <label>
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $article?->is_published ?? true))>
        <span><strong>Published on the public journal</strong><br>Only enable when the article is complete, original, and ready for visitors.</span>
    </label>
    <label>
        <input type="checkbox" name="is_popular" value="1" @checked(old('is_popular', $article?->is_popular ?? false))>
        <span><strong>Show in popular list</strong><br>Feature notes that help readers take a clear next step.</span>
    </label>
</div>
