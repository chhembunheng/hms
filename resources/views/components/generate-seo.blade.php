@props(['form' => null])
<div class="row">
    <div class="col-md-8 offset-md-2">
        <x-form.input :label="__('form.slug')" name="slug" value="{{ old('slug', $form?->slug) }}" />
    </div>
    <div class="col-md-8 offset-md-2">
        <h4 class="mt-4">{{ __('form.meta.meta_information') }} <i class="ms-2 fa-solid fa-wand-magic-sparkles cursor-pointer text-success meta-generator"></i></h4>
        <hr>
    </div>
    <div class="col-md-8 offset-md-2">
        <x-form.input :label="__('form.meta.title')" name="meta[title]" :value="old('meta.title', $form?->meta?->title)" />
    </div>
    <div class="col-md-8 offset-md-2">
        <x-form.input :label="__('form.meta.description')" name="meta[description]" :value="old('meta.description', $form?->meta?->description)" />
    </div>
    <div class="col-md-8 offset-md-2">
        <x-form.input :label="__('form.meta.keywords')" name="meta[keywords]" :value="old('meta.keywords', $form?->meta?->keywords)" placeholder="keyword1, keyword2, keyword3" />
    </div>
    <div class="col-md-8 offset-md-2 text-end mt-3">
        <button type="submit" class="btn btn-primary">{{ __('form.save') }}</button>
    </div>
</div>