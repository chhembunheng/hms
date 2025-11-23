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
            <div class="col-md-6">
                <x-form.checkbox :label="__('form.is_slider')" name="is_slider" value="1" :checked="old('is_slider', $form?->is_slider)" />
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
        
        <!-- Slider Section -->
        <div id="slider-section" style="display: @if (old('is_slider', $form?->is_slider)) block @else none @endif;">
            <hr class="my-4">
            <h4 class="mb-3">{{ __('form.slider_content') }}</h4>
            
            <!-- Slider Image -->
            <div class="row mb-4">
                <div class="col-12">
                    <x-form.input :label="__('form.slider_image')" type="file" name="slider_image" accept="image/*" id="service-slider-image"
                        :initialPreview="$form?->slider_image ? asset($form->slider_image) : null" :initialCaption="$form?->slider_image ? basename($form->slider_image) : null" />
                </div>
            </div>
            
            <!-- Slider Translation Tabs -->
            <div class="mb-3">
                <ul class="nav nav-tabs" role="tablist">
                    @foreach ($locales as $locale => $language)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if ($locale === config('app.locale')) active @endif"
                                id="slider-locale-{{ $locale }}-tab" data-bs-toggle="tab"
                                data-bs-target="#slider-locale-{{ $locale }}" type="button" role="tab"
                                aria-controls="slider-locale-{{ $locale }}"
                                aria-selected="@if ($locale === config('app.locale')) true @else false @endif">
                                <img src="{{ asset($language['flag']) }}" class="lang-flag me-2"><span
                                    class="fs-lg">{{ $language['name'] }}</span>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            <div class="tab-content" id="sliderTabContent">
                @foreach ($locales as $locale => $language)
                    <div @class([
                        'tab-pane fade',
                        'show active' => $locale === config('app.locale'),
                    ]) id="slider-locale-{{ $locale }}" role="tabpanel"
                        aria-labelledby="slider-locale-{{ $locale }}-tab">
                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                <x-form.input :label="__('form.slider_title')" name="translations[{{ $locale }}][slider_title]"
                                    :value="old(
                                        'translations.' . $locale . '.slider_title',
                                        $translations[$locale]['slider_title'] ?? '',
                                    )" />
                            </div>
                            <div class="col-md-8 offset-md-2">
                                <x-form.textarea :label="__('form.slider_description')" name="translations[{{ $locale }}][slider_description]"
                                    :value="old(
                                        'translations.' . $locale . '.slider_description',
                                        $translations[$locale]['slider_description'] ?? '',
                                    )" rows="3" />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <x-generate-seo :form="$form" />
    </x-form.layout>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isSliderCheckbox = document.querySelector('input[name="is_slider"]');
            const sliderSection = document.getElementById('slider-section');

            function toggleSliderSection() {
                sliderSection.style.display = isSliderCheckbox.checked ? 'block' : 'none';
            }

            isSliderCheckbox.addEventListener('change', toggleSliderSection);
        });
    </script>
</x-app-layout>
