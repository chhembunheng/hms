<x-app-layout>
    <x-form.layout :form="$form">
        <div class="row">
            <div class="col-md-6">
                <x-form.input :label="__('form.sort')" type="number" name="sort" value="{{ $form?->sort ?? 0 }}" min="0" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.author')" name="author" value="{{ old('author', $form?->author) }}" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.date')" type="date" name="date" value="{{ old('date', optional($form?->date)->format('Y-m-d')) }}" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.categories')" name="categories" value="{{ old('categories', isset($form?->categories) ? implode(', ', (array) $form->categories) : '') }}" placeholder="IT Solutions, Cloud" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.tags')" name="tags" value="{{ old('tags', isset($form?->tags) ? implode(', ', (array) $form->tags) : '') }}" placeholder="security, compliance, growth" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.link')" type="url" name="link" value="{{ old('link', $form?->link) }}" placeholder="# or https://..." />
            </div>
            <div class="col-md-12">
                <x-form.checkbox :label="__('form.published')" id="is_published" name="is_published" value="1" checked="{{ $form?->is_published }}" checked />
            </div>
        </div>
        <hr class="my-4">
        <div class="row">
            <div class="col-6">
                <x-form.input :label="__('form.thumbnail')" type="file" name="thumbnail" accept="image/*" :initialPreview="$form?->thumbnail ? asset($form->thumbnail) : null" :initialCaption="$form?->thumbnail ? basename($form->thumbnail) : null" :initialSize="$form?->thumbnail ? getFileSize($form->thumbnail) : null" />
            </div>
            <div class="col-6">
                <x-form.input :label="__('form.image_cover')" type="file" name="image_cover" accept="image/*" :initialPreview="$form?->image_cover ? asset($form->image_cover) : null" :initialCaption="$form?->image_cover ? basename($form->image_cover) : null" :initialSize="$form?->image_cover ? getFileSize($form->image_cover) : null" />
            </div>
        </div>
        <hr class="my-4">
        <div class="mb-3">
            <ul class="nav nav-tabs" role="tablist">
                @foreach ($locales as $locale => $language)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if ($locale === config('app.locale')) active @endif" id="locale-{{ $locale }}-tab" data-bs-toggle="tab" data-bs-target="#locale-{{ $locale }}" type="button" role="tab" aria-controls="locale-{{ $locale }}"
                            aria-selected="@if ($locale === config('app.locale')) true @else false @endif">
                            <img src="{{ asset($language['flag']) }}" class="lang-flag me-2"><span class="fs-lg">{{ $language['name'] }}</span>
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="tab-content" id="localeTabContent">
            @foreach ($locales as $locale => $language)
                <div class="tab-pane fade @if ($locale === config('app.locale')) show active @endif" id="locale-{{ $locale }}" role="tabpanel" aria-labelledby="locale-{{ $locale }}-tab">
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <x-form.input class="@if ($locale === config('app.locale')) content-seo @endif" :label="__('form.title')" name="translations[{{ $locale }}][title]" :value="old('translations.' . $locale . '.title', $translations[$locale]['title'] ?? '')" required />
                        </div>
                        <div class="col-md-8 offset-md-2">
                            <x-form.textarea :label="__('form.excerpt')" name="translations[{{ $locale }}][excerpt]" :value="old('translations.' . $locale . '.excerpt', $translations[$locale]['excerpt'] ?? '')" rows="2" />
                        </div>
                        <div class="col-md-8 offset-md-2">
                            <x-form.textarea :label="__('form.body')" name="translations[{{ $locale }}][body]" :value="old('translations.' . $locale . '.body', $translations[$locale]['body'] ?? '')" rows="6" class="editor" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <x-form.input :label="__('form.slug')" name="slug" value="{{ old('slug', $form?->slug) }}" />
            </div>
            <div class="col-md-8 offset-md-2">
                <h4 class="mt-4">{{ __('form.meta.meta_information') }} <i class="ms-2 fa-solid fa-wand-magic-sparkles cursor-pointer text-success meta-generator"></i></h4>
                <hr>
            </div>
            <div class="col-md-8 offset-md-2">
                <x-form.input :label="__('form.meta.title')" name="meta[title]" :value="old('meta.title', $form?->meta?->title)" />
            </div>
            <div class="col-md-8 offset-md-2">
                <x-form.input :label="__('form.meta.description')" name="meta[description]" :value="old('meta.description', $form?->meta?->description)" />
            </div>
            <div class="col-md-8 offset-md-2">
                <x-form.input :label="__('form.meta.keywords')" name="meta[keywords]" :value="old('meta.keywords', $form?->meta?->keywords)" placeholder="keyword1, keyword2, keyword3" />
            </div>
            <div class="col-md-8 offset-md-2 text-end mt-3">
                <button type="submit" class="btn btn-primary">{{ __('form.save') }}</button>
            </div>
        </div>
    </x-form.layout>
</x-app-layout>
