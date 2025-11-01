<x-app-layout>
    <x-form.layout :form="$form">
        <!-- Locale Tabs for Translatable Fields -->
        <div class="mb-3">
            <ul class="nav nav-tabs" role="tablist">
                @foreach ($locales as $index => $locale)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($index === 0) active @endif" id="locale-{{ $locale }}-tab" data-bs-toggle="tab" data-bs-target="#locale-{{ $locale }}" type="button" role="tab" aria-controls="locale-{{ $locale }}" aria-selected="@if($index === 0) true @else false @endif">
                            {{ strtoupper($locale) }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="tab-content" id="localeTabContent">
            @foreach ($locales as $index => $locale)
                <div class="tab-pane fade @if($index === 0) show active @endif" id="locale-{{ $locale }}" role="tabpanel" aria-labelledby="locale-{{ $locale }}-tab">
                    <div class="row">
                        <div class="col-md-6">
                            <x-form.input 
                                :label="__('form.first_name')" 
                                name="first_name[{{ $locale }}]" 
                                value="{{ $translations[$locale]['first_name'] ?? '' }}" 
                                required 
                            />
                        </div>
                        <div class="col-md-6">
                            <x-form.input 
                                :label="__('form.last_name')" 
                                name="last_name[{{ $locale }}]" 
                                value="{{ $translations[$locale]['last_name'] ?? '' }}" 
                                required 
                            />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <hr class="my-4">

        <div class="row">
            <div class="col-md-6">
                <x-form.input 
                    :label="__('form.username')" 
                    name="username" 
                    value="{{ $form?->username }}" 
                    required 
                />
            </div>
            <div class="col-md-6">
                <x-form.input 
                    :label="__('form.email')" 
                    type="email" 
                    name="email" 
                    value="{{ $form?->email }}" 
                    required 
                />
            </div>
            <div class="col-md-6">
                <x-form.input 
                    :label="__('form.phone')" 
                    type="tel" 
                    name="phone" 
                    value="{{ $form?->phone }}" 
                />
            </div>
            <div class="col-md-6">
                <x-form.select
                    :label="__('form.gender')"
                    name="gender"
                    value="{{ $form?->gender }}"
                >
                    <option value="">{{ __('form.select') }}</option>
                    <option value="male" @selected($form?->gender === 'male')>Male</option>
                    <option value="female" @selected($form?->gender === 'female')>Female</option>
                    <option value="other" @selected($form?->gender === 'other')>Other</option>
                </x-form.select>
            </div>
            <div class="col-md-6">
                <x-form.input 
                    :label="__('form.address')" 
                    name="address" 
                    value="{{ $form?->address }}" 
                />
            </div>
            @if ($form?->id == null)
                <div class="col-md-6">
                    <x-form.input 
                        :label="__('form.password')" 
                        type="password" 
                        name="password" 
                        autocomplete="new-password" 
                        required 
                    />
                </div>
                <div class="col-md-6">
                    <x-form.input 
                        :label="__('form.password_confirmation')" 
                        type="password" 
                        name="password_confirmation" 
                        autocomplete="new-password" 
                        required 
                    />
                </div>
            @else
                <div class="col-md-6">
                    <x-form.input 
                        :label="__('form.password')" 
                        type="password" 
                        name="password" 
                        autocomplete="new-password" 
                        placeholder="Leave blank to keep current password"
                    />
                </div>
                <div class="col-md-6">
                    <x-form.input 
                        :label="__('form.password_confirmation')" 
                        type="password" 
                        name="password_confirmation" 
                        autocomplete="new-password" 
                        placeholder="Leave blank to keep current password"
                    />
                </div>
            @endif
        </div>

        <div class="text-end mt-3">
            <button type="submit" class="btn btn-primary">{{ __('form.save') }}</button>
        </div>
    </x-form.layout>
</x-app-layout>
