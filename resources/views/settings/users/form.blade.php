<x-app-layout>
    <x-form.layout :form="$form">
        <div class="container-fluid py-3">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-3" style="border-bottom: 2px solid #e9ecef;">
                                @foreach ($locales as $locale => $lang)
                                    <li class="nav-item">
                                        <button type="button" class="nav-link @if ($loop->first) active @endif" id="locale-{{ $locale }}-tab" data-bs-toggle="tab" data-bs-target="#locale-{{ $locale }}" type="button" role="tab" aria-controls="locale-{{ $locale }}" aria-selected="@if ($loop->first) true @else false @endif">
                                            <img src="{{ asset($lang['flag']) }}" class="lang-flag me-2" style="height:18px;"> <span class="fs-lg">{{ $lang['name'] }}</span>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content" id="localeTabContent">
                                @foreach ($locales as $locale => $lang)
                                    <div class="tab-pane fade @if ($loop->first) show active @endif" id="locale-{{ $locale }}" role="tabpanel" aria-labelledby="locale-{{ $locale }}-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <x-form.input :label="__('form.first_name')" name="first_name[{{ $locale }}]" value="{{ $translations[$locale]['first_name'] ?? '' }}" required />
                                            </div>
                                            <div class="col-md-6">
                                                <x-form.input :label="__('form.last_name')" name="last_name[{{ $locale }}]" value="{{ $translations[$locale]['last_name'] ?? '' }}" required />
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <div class="row g-4 align-items-center">
                                <div class="col-md-4">
                                    <x-form.input :label="__('form.username')" name="username" value="{{ $form?->username }}" required />
                                </div>
                                <div class="col-md-4">
                                    <x-form.input :label="__('form.email')" type="email" name="email" value="{{ $form?->email }}" required />
                                </div>
                                <div class="col-md-4">
                                    <x-form.input :label="__('form.phone')" type="tel" name="phone" value="{{ $form?->phone }}" />
                                </div>
                                <div class="col-md-4">
                                    <x-form.select :label="__('form.gender')" name="gender" value="{{ $form?->gender }}"
                                        :selected="old('gender', $form?->gender)"
                                        :options="['male' => 'Male', 'female' => 'Female']">
                                    </x-form.select>
                                </div>
                                <div class="col-md-8">
                                    <x-form.input :label="__('form.address')" name="address" value="{{ $form?->address }}" />
                                </div>
                                <div class="col-md-12">
                                    <x-form.select
                                        :label="__('form.role')"
                                        name="roles[]"
                                        multiple
                                        required
                                        :options="$roles->mapWithKeys(fn($role) => [
                                            $role->id => $role->translations->first()->name ?? ('Role #'.$role->id)
                                        ])"
                                        :selected="isset($form) && $form->roles ? $form->roles->pluck('id')->toArray() : []"
                                    />
                                </div>
                                @if ($form?->id == null)
                                    <div class="col-md-6">
                                        <x-form.input :label="__('form.password')" type="password" name="password" autocomplete="new-password" required />
                                    </div>
                                    <div class="col-md-6">
                                        <x-form.input :label="__('form.password_confirmation')" type="password" name="password_confirmation" autocomplete="new-password" required />
                                    </div>
                                @else
                                    <div class="col-md-6">
                                        <x-form.input :label="__('form.password')" type="password" name="password" autocomplete="new-password" placeholder="Leave blank to keep current password" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-form.input :label="__('form.password_confirmation')" type="password" name="password_confirmation" autocomplete="new-password" placeholder="Leave blank to keep current password" />
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary px-4 py-2">{{ __('form.save') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </x-form.layout>
</x-app-layout>
