<x-app-layout>
    <x-form.layout :form="$form">
        <div class="row">
            <div class="col-md-6">
                <x-form.input :label="__('form.logo')" type="file" name="logo" accept="image/*" />
                @if($form?->logo)
                    <small class="d-block mt-2">
                        <img src="{{ asset($form->logo) }}" alt="{{ $form->getName() }}" style="max-width: 150px; max-height: 150px;">
                    </small>
                @endif
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.website_url')" type="url" name="website_url" value="{{ $form?->website_url ?? old('website_url') }}" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.sort')" type="number" name="sort" value="{{ $form?->sort ?? 0 }}" min="0" />
            </div>
            <div class="col-md-12 mt-3">
                <x-form.checkbox :label="__('form.active')" id="is_active" name="is_active" value="1" checked="{{ $form?->is_active ?? true }}" />
            </div>
        </div>

        <hr class="my-4">

        <!-- Translations Tabs -->
        <div class="mb-3">
            <ul class="nav nav-tabs" role="tablist">
                @foreach ($locales as $locale => $language)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if ($locale === config('app.locale')) active @endif" id="locale-{{ $locale }}-tab" data-bs-toggle="tab" data-bs-target="#locale-{{ $locale }}" type="button" role="tab" aria-controls="locale-{{ $locale }}" aria-selected="@if ($locale === config('app.locale')) true @else false @endif">
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
                            <x-form.textarea :label="__('form.description')" name="translations[{{ $locale }}][description]" :value="old('translations.' . $locale . '.description', $translations[$locale]['description'] ?? '')" rows="3" />
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
