@props(['form' => null, 'locales' => []])
<div class="col-md-12">
    <x-form.checkbox :label="__('form.is_slider')" name="is_slider" value="1" :checked="old('is_slider', $form?->is_slider)" />
</div>
<!-- Slider Section -->
<div id="slider-section" style="display: @if (old('is_slider', $form?->is_slider)) block @else none @endif;">
    <hr class="my-4">
    <h4 class="mb-3">{{ __('form.slider_content') }}</h4>
    <!-- Slider Image -->
    <div class="row mb-4">
        <div class="col-md-6">
            <x-form.input :label="__('form.slider_image')" type="file" name="slider_image" accept="image/*" id="product-slider-image"
                :initialPreview="$form?->slider_image ? asset($form->slider_image) : null" :initialCaption="$form?->slider_image ? basename($form->slider_image) : null" />
        </div>
        <div class="col-md-6">
            <!-- Slider Translation Tabs -->
            <div class="mb-3 mt-4">
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
                        <x-form.input :label="__('form.slider_title')" name="translations[{{ $locale }}][slider_title]"
                            :value="old(
                                'translations.' . $locale . '.slider_title',
                                $translations[$locale]['slider_title'] ?? '',
                            )" lang="{{ $locale }}" />
                        <x-form.textarea :label="__('form.slider_description')" name="translations[{{ $locale }}][slider_description]"
                            :value="old(
                                'translations.' . $locale . '.slider_description',
                                $translations[$locale]['slider_description'] ?? '',
                            )" rows="3" lang="{{ $locale }}" />
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
