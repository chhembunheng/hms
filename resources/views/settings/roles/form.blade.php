<x-app-layout>
    <x-form.layout :form="$form">
        <div class="row">
            <div class="col-md-6">
                <x-form.input :label="__('form.order')" name="order" type="number" value="{{ old('sort', $form?->order ?? 0) }}" min="0" />
            </div>
            <div class="col-md-12 mt-3">
                <x-form.checkbox :label="__('form.administrator')" id="administrator" name="administrator" value="1" checked="{{ $form?->administrator }}" />
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
                <div @class([
                    'tab-pane fade',
                    'show active' => $locale === config('app.locale'),
                ]) id="locale-{{ $locale }}" role="tabpanel" aria-labelledby="locale-{{ $locale }}-tab">
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <x-form.input :label="__('form.name')" name="name[{{ $locale }}]" value="{{ old('name.' . $locale, $translations[$locale]['name'] ?? '') }}" required />
                        </div>
                        <div class="col-md-8 offset-md-2">
                            <x-form.textarea :label="__('form.description')" name="description[{{ $locale }}]" value="{{ old('description.' . $locale, $translations[$locale]['description'] ?? '') }}" rows="3" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{-- Permissions tree --}}
        <div class="row mt-3">
            <div class="col-md-10 offset-md-1">
                @include('settings.roles._permissions_tree', ['form' => $form])
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 offset-md-2 text-end mt-3">
                <button type="submit" class="btn btn-primary">{{ __('form.save') }}</button>
            </div>
        </div>
    </x-form.layout>
</x-app-layout>
