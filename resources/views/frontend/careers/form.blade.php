<x-app-layout>
    <x-form.layout :form="$form">
        <div class="row">
            <div class="col-md-6">
                <x-form.input :label="__('form.location')" name="location" value="{{ old('location', $form?->location) }}" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.deadline')" type="date" name="deadline" value="{{ old('deadline', optional($form?->deadline)->format('Y-m-d')) }}" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.position')" name="position" value="{{ old('position', $form?->position) }}" />
            </div>
            <div class="col-md-6">
                <x-form.select :label="__('form.type')" name="type" :options="['full_time' => 'Full time', 'part_time' => 'Part time', 'internship' => 'Internship']" :selected="old('type', $form?->type)" />
            </div>
            <div class="col-md-6">
                <x-form.select :label="__('form.level')" name="level" :options="['intern' => 'Intern', 'junior' => 'Junior', 'mid' => 'Mid', 'senior' => 'Senior']" :selected="old('level', $form?->level)" />
            </div>
            <div class="col-md-6">
                <x-form.select :label="__('form.priority')" name="priority" :options="['low' => 'Low', 'medium' => 'Regular', 'high' => 'Urgent']" :selected="old('priority', $form?->priority)" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.icon')" name="icon" value="{{ old('icon', $form?->icon) }}" placeholder="fas fa-code" />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.link')" type="url" name="link" value="{{ old('link', $form?->link) }}" placeholder="# or https://..." />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.sort')" type="number" name="sort" value="{{ old('sort', $form?->sort ?? 0) }}" min="0" />
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
                            <x-form.input :label="__('form.title')" name="translations[{{ $locale }}][title]" :value="old('translations.' . $locale . '.title', $translations[$locale]['title'] ?? '')" required />
                        </div>
                        <div class="col-md-8 offset-md-2">
                            <x-form.textarea :label="__('form.short_description')" name="translations[{{ $locale }}][short_description]" :value="old('translations.' . $locale . '.short_description', $translations[$locale]['short_description'] ?? '')" rows="2" />
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
                <x-form.input :label="__('form.slug')" name="slug" :value="old('slug', $form?->slug ?? '')" required />
            </div>
            <div class="col-md-8 offset-md-2 text-end mt-3">
                <button type="submit" class="btn btn-primary">{{ __('form.save') }}</button>
            </div>
        </div>
    </x-form.layout>
</x-app-layout>
