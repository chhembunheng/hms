<x-app-layout>
    <x-form.layout :form="$form">
        <div class="row">
            <div class="col-md-6">
                <x-form.select :label="__('form.parent_navigation')" name="parent_id" :options="$navigations" :selected="$form?->parent_id ?? old('parent_id')">
                </x-form.select>
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.url')" name="url" value="{{ $form?->url ?? old('url') }}" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.icon')" name="icon" placeholder="e.g., fa-home" value="{{ $form?->icon ?? old('icon') }}" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.sort')" type="number" name="sort" value="{{ $form?->sort ?? old('sort', 0) }}" />
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
                            <x-form.input :label="__('form.label')" name="translations[{{ $locale }}][label]" :value="old('translations.' . $locale . '.label', $translations[$locale]['label'] ?? '')" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-md-8 offset-md-2 text-end mt-3">
                <button type="submit" class="btn btn-primary">{{ __('form.save') }}</button>
            </div>
        </div>
    </x-form.layout>
</x-app-layout>
