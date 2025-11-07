<x-app-layout>
    <x-form.layout :form="$form">
        <div class="row">
            <div class="col-md-6">
                <x-form.select :label="__('form.parent_menu')" name="menu_id">
                    <option value="">{{ __('form.select_menu') }}</option>
                    @foreach($menus as $menu)
                        <option value="{{ $menu->id }}" @selected(old('menu_id', $form?->menu_id) === $menu->id)>
                            @if($menu->parent_id)
                                &nbsp;&nbsp;&nbsp;&nbsp;
                            @endif
                            {{ $menu->translations->where('locale', app()->getLocale())->first()?->name ?? $menu->translations->where('locale', 'en')->first()?->name ?? 'Untitled' }}
                        </option>
                    @endforeach
                </x-form.select>
            </div>
            <div class="col-md-6">
                <x-form.select :label="__('form.target')" name="target">
                    <option value="">{{ __('form.select') }}</option>
                    <option value="navbar" @selected(old('target', $form?->target) === 'navbar')>Navbar</option>
                    <option value="self" @selected(old('target', $form?->target) === 'self')>Self</option>
                    <option value="confirm" @selected(old('target', $form?->target) === 'confirm')>Confirm</option>
                </x-form.select>
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.icon')" name="icon" value="{{ old('icon', $form?->icon ?? '') }}" placeholder="e.g., fa-check" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.action_route')" name="action_route" value="{{ old('action_route', $form?->action_route ?? '') }}" placeholder="e.g., settings.menus.add" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.order')" type="number" name="order" value="{{ old('sort', $form?->order ?? 0) }}" min="0" required />
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
            <button type="submit" class="btn btn-primary">{{ __('form.save') }}</button>
        </div>
    </x-form.layout>
</x-app-layout>
