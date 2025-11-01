<x-app-layout>
    <x-form.layout :form="$form">
        <div class="row">
            <div class="col-md-6">
                <x-form.input :label="__('form.icon')" name="icon" value="{{ $form?->icon ?? old('icon') }}" placeholder="fa-solid fa-cog" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.sort')" type="number" name="sort" value="{{ $form?->sort ?? 0 }}" min="0" />
            </div>
            <div class="col-md-12">
                <x-form.input :label="__('form.image')" type="file" name="image" accept="image/*" />
                @if ($form?->image)
                    <small class="d-block mt-2">
                        <img src="{{ asset($form->image) }}" alt="{{ $form->getName() }}" style="max-width: 150px; max-height: 150px;">
                    </small>
                @endif
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
                            <x-form.input :label="__('form.name')" name="translations[{{ $locale }}][name]" :value="old('translations.' . $locale . '.name', $translations[$locale]['name'] ?? '')" required />
                        </div>
                        <div class="col-md-8 offset-md-2">
                            <x-form.textarea :label="__('form.short_description')" name="translations[{{ $locale }}][short_description]" :value="old('translations.' . $locale . '.short_description', $translations[$locale]['short_description'] ?? '')" rows="2" />
                        </div>
                        <div class="col-md-8 offset-md-2">
                            <x-form.textarea :label="__('form.description')" name="translations[{{ $locale }}][description]" :value="old('translations.' . $locale . '.description', $translations[$locale]['description'] ?? '')" rows="5" class="editor" />
                        </div>
                        <div class="col-md-8 offset-md-2">
                            <x-form.textarea :label="__('form.content')" name="translations[{{ $locale }}][content]" :value="old('translations.' . $locale . '.content', $translations[$locale]['content'] ?? '')" rows="6" class="editor" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <x-form.input :label="__('form.slug')" name="slug" value="{{ old('slug', $form?->slug) }}" required />
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
