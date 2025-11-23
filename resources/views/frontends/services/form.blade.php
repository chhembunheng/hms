<x-app-layout>
    <x-form.layout :form="$form">
        <div class="row">
            <div class="col-md-6">
                <x-form.icon id="icon-service" :label="__('form.icon')" name="icon" value="{{ $form?->icon ?? old('icon') }}"
                    placeholder="fa-solid fa-cog" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.sort')" type="number" name="sort" value="{{ $form?->sort ?? 0 }}"
                    min="0" />
            </div>
        </div>
        <hr class="my-4">
        <div class="row">
            <div class="col-6">
                <x-form.input :label="__('form.image')" type="file" name="image" accept="image/*" id="service-image"
                    :initialPreview="$form?->image ? asset($form->image) : null" :initialCaption="$form?->image ? basename($form->image) : null" />
            </div>
            <div class="col-6">
                <x-form.input :label="__('form.image')" type="file" name="image_cover" accept="image/*"
                    id="service-image-cover" :initialPreview="$form?->image_cover ? asset($form->image_cover) : null" :initialCaption="$form?->image_cover ? basename($form->image_cover) : null" />
            </div>
        </div>
        <hr class="my-4">
        <div class="mb-3">
            <ul class="nav nav-tabs" role="tablist">
                @foreach ($locales as $locale => $language)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if ($locale === config('app.locale')) active @endif"
                            id="locale-{{ $locale }}-tab" data-bs-toggle="tab"
                            data-bs-target="#locale-{{ $locale }}" type="button" role="tab"
                            aria-controls="locale-{{ $locale }}"
                            aria-selected="@if ($locale === config('app.locale')) true @else false @endif">
                            <img src="{{ asset($language['flag']) }}" class="lang-flag me-2"><span
                                class="fs-lg">{{ $language['name'] }}</span>
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="tab-content" id="localeTabContent">
            @foreach ($locales as $locale => $language)
                <div @class([
                    'tab-pane fade',
                    'show active' => $locale === config('app.locale'),
                ]) id="locale-{{ $locale }}" role="tabpanel"
                    aria-labelledby="locale-{{ $locale }}-tab">
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <x-form.input @class(['content-seo' => $locale === config('app.locale')]) :label="__('form.name')"
                                name="translations[{{ $locale }}][name]" :value="old(
                                    'translations.' . $locale . '.name',
                                    $translations[$locale]['name'] ?? '',
                                )" required />
                        </div>
                        <div class="col-md-8 offset-md-2">
                            <x-form.textarea :label="__('form.content')"
                                name="translations[{{ $locale }}][content]" :value="old(
                                    'translations.' . $locale . '.content',
                                    $translations[$locale]['content'] ?? '',
                                )"
                                rows="2" />
                        </div>
                        <div class="col-md-8 offset-md-2">
                            <x-form.textarea :label="__('form.description')" name="translations[{{ $locale }}][description]"
                                :value="old(
                                    'translations.' . $locale . '.description',
                                    $translations[$locale]['description'] ?? '',
                                )" rows="5" class="editor" />
                        </div>
                        <div class="col-md-8 offset-md-2">
                            <x-form.textarea :label="__('form.content')" name="translations[{{ $locale }}][content]"
                                :value="old(
                                    'translations.' . $locale . '.content',
                                    $translations[$locale]['content'] ?? '',
                                )" rows="6" class="editor" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <x-generate-seo :form="$form" />
    </x-form.layout>
</x-app-layout>
