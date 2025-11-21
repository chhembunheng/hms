<x-app-layout>
    <x-form.layout :form="$form">
        <div class="row">
            <div class="col-md-6">
                <x-form.icon :label="__('form.icon')" name="icon" value="{{ old('icon', $form?->icon) }}" />
            </div>
            <div class="col-md-6">
                <x-form.select :label="__('form.navigation')" name="navigation_id" :selected="old('navigation_id', $form?->navigation?->parent_id)"
                    :options="$navigations" data-placeholder="Select a navigation" />
            </div>
            <div class="col-md-6">
                <x-form.select multiple data-searchable="false" :label="__('form.category')" name="category_id[]" :selected="$form?->categories?->pluck('id')->toArray()"
                    data-placeholder="Select a category" :options="$categories" required />
            </div>
            <div class="col-md-6">
                <x-form.select multiple data-searchable="false" :label="__('form.tags')" name="tag_id[]" :selected="$form?->tags?->pluck('id')->toArray()"
                    data-placeholder="Select tags" :options="$tags" required />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.sort')" type="number" name="sort" value="{{ $form?->sort ?? 0 }}"
                    min="0" />
            </div>
        </div>
        <hr class="my-4">
        <div class="row">
            <div class="col-12">
                <x-form.input :label="__('form.image')" type="file" name="image" accept="image/*" :initialPreview="$form?->image ? asset($form->image) : null"
                    :initialCaption="$form?->image ? basename($form->image) : null" :initialSize="$form?->image ? getFileSize($form->image) : null" />
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
                            <x-form.textarea :label="__('form.short_description')"
                                name="translations[{{ $locale }}][short_description]" :value="old(
                                    'translations.' . $locale . '.short_description',
                                    $translations[$locale]['short_description'] ?? '',
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
                                )" rows="6" class="editor content-{{ $locale }}" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <x-generate-seo :form="$form" />
    </x-form.layout>
</x-app-layout>
