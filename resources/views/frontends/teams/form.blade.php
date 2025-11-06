<x-app-layout>
    <x-form.layout :form="$form">
        <div class="row">
            <div class="col-md-6">
                <x-form.input :label="__('form.sort')" type="number" name="sort" value="{{ $form?->sort ?? 0 }}" min="0" />
            </div>
            <div class="col-md-6">
                <x-form.select :label="__('form.position')" name="position_id" :options="$positions" :selected="old('position_id', $form?->position_id)" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.telegram')" type="url" name="telegram" value="{{ old('telegram', $form?->telegram) }}" placeholder="https://t.me/username" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.twitter')" type="url" name="twitter" value="{{ old('twitter', $form?->twitter) }}" placeholder="https://x.com/username" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.facebook')" type="url" name="facebook" value="{{ old('facebook', $form?->facebook) }}" placeholder="https://facebook.com/username" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.linkedin')" type="url" name="linkedin" value="{{ old('linkedin', $form?->linkedin) }}" placeholder="https://linkedin.com/in/username" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.github')" type="url" name="github" value="{{ old('github', $form?->github) }}" placeholder="https://github.com/username" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.email')" type="email" name="email" value="{{ old('email', $form?->email) }}" placeholder="name@example.com" />
            </div>
        </div>
        <hr class="my-4">
        <div class="row">
            <div class="col-12">
                <x-form.input :label="__('form.photo')" type="file" name="photo" accept="image/*" :initialPreview="$form?->photo ? asset($form->photo) : null" :initialCaption="$form?->photo ? basename($form->photo) : null" :initialSize="$form?->photo ? getFileSize($form->photo) : null" />
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
                <div @class(['tab-pane fade', 'show active' => $locale === config('app.locale')]) id="locale-{{ $locale }}" role="tabpanel" aria-labelledby="locale-{{ $locale }}-tab">
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <x-form.input @class(['content-seo' => $locale === config('app.locale')]) :label="__('form.name')" name="translations[{{ $locale }}][name]" :value="old('translations.' . $locale . '.name', $translations[$locale]['name'] ?? '')" required />
                        </div>
                        <div class="col-md-8 offset-md-2">
                            <x-form.textarea :label="__('form.description')" name="translations[{{ $locale }}][description]" :value="old('translations.' . $locale . '.description', $translations[$locale]['description'] ?? '')" rows="5" class="editor" />
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
