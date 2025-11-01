<x-app-layout>
    <x-form.layout :form="$form">
        <div class="row">
            <div class="col-md-6">
                <x-form.input :label="__('form.icon')" name="icon" value="{{ old('icon', $form?->icon) }}" placeholder="fa-solid fa-home" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.route')" name="route" value="{{ old('route', $form?->route) }}" placeholder="dashboard.index" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.order')" name="order" type="number" value="{{ old('order', $form?->order ?? 0) }}" min="0" />
            </div>
            <div class="col-md-6">
                <x-form.select :label="__('form.parent_menu')" name="parent_id">
                    <option value="">{{ __('form.select') }}</option>
                    @foreach ($menus as $menu)
                        @php
                            $menuName = $menu->translations->where('locale', app()->getLocale())->first()?->name 
                                     ?? $menu->translations->where('locale', 'en')->first()?->name 
                                     ?? 'N/A';
                        @endphp
                        <option value="{{ $menu->id }}" @selected(old('parent_id', $form?->parent_id) === $menu->id)>
                            {{ $menuName }}
                        </option>
                    @endforeach
                </x-form.select>
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
                            <x-form.input :label="__('form.name')" name="name[{{ $locale }}]" value="{{ old('name.' . $locale, $translations[$locale]['name'] ?? '') }}" required />
                        </div>
                        <div class="col-md-8 offset-md-2">
                            <x-form.textarea :label="__('form.description')" name="description[{{ $locale }}]" value="{{ old('description.' . $locale, $translations[$locale]['description'] ?? '') }}" rows="3" />
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
