<x-app-layout>
    <x-form.layout :form="$form">
        <div class="container-fluid py-3">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <div class="row g-4 align-items-center">
                                <div class="col-md-3">
                                    <x-form.input :label="__('form.order')" name="sort" type="number" value="{{ old('sort', $form?->sort ?? 0) }}" min="0" />
                                </div>
                                <div class="col-md-3">
                                    <x-form.checkbox :label="__('form.administrator')" id="administrator" name="administrator" value="1" checked="{{ $form?->administrator }}" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-3" style="border-bottom: 2px solid #e9ecef;">
                                @foreach ($locales as $locale => $language)
                                    <li class="nav-item">
                                        <button type="button" class="nav-link @if ($locale === config('app.locale')) active @endif" id="locale-{{ $locale }}-tab" data-bs-toggle="tab" data-bs-target="#locale-{{ $locale }}" type="button" role="tab" aria-controls="locale-{{ $locale }}" aria-selected="@if ($locale === config('app.locale')) true @else false @endif">
                                            <img src="{{ asset($language['flag']) }}" class="lang-flag me-2" style="height:18px;"> <span class="fs-lg">{{ $language['name'] }}</span>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content" id="localeTabContent">
                                @foreach ($locales as $locale => $language)
                                    <div @class([
                                        'tab-pane fade',
                                        'show active' => $locale === config('app.locale'),
                                    ]) id="locale-{{ $locale }}" role="tabpanel" aria-labelledby="locale-{{ $locale }}-tab">
                                        <div class="row justify-content-center">
                                            <div class="col-md-8 my-2">
                                                <x-form.input :label="__('form.name')" name="name[{{ $locale }}]" value="{{ old('name.' . $locale, ($translations[$locale]['name'] ?? '') ?? '') }}" required />
                                            </div>
                                            <div class="col-md-8 my-2">
                                                <x-form.textarea :label="__('form.description')" name="description[{{ $locale }}]" value="{{ old('description.' . $locale, ($translations[$locale]['description'] ?? '') ?? '') }}" rows="3" />
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">{{ __('form.permissions') }}</h5>
                            @include('settings.roles._permissions_tree', ['form' => $form])
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4 py-2">{{ __('form.save') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </x-form.layout>
</x-app-layout>
