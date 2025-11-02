@props(['label', 'required' => $attributes->has('required') && $attributes->get('required') === 'required', 'initialPreview' => null, 'initialCaption' => null, 'initialSize' => null])
@if ($attributes->has('accept') && $attributes->get('accept') === 'image/*')
    <div class="mb-3">
        <p class="fw-semibold @if ($required) required @endif">{{ $label ?? '' }}</p>
        <input type="file" 
            {{ $attributes->merge(['class' => 'form-control-sm file-input file-input-open-editor kv-fileinput-caption']) }} 
            data-input-group-class="input-group-sm"
            data-upload-url="false"
            @if ($initialPreview) data-initial-preview="{{ $initialPreview }}" @endif
            @if ($initialCaption) data-initial-caption="{{ $initialCaption }}" @endif
            @if ($initialSize) data-initial-preview-file-size="{{ $initialSize }}" @endif
            @if ($required) required @endif>
    </div>
@else
    <div class="mb-3">
        <label class="form-label @if ($required) required @endif">{{ $label ?? '' }}</label>
        <input @if ($attributes->has('type')) type="{{ $attributes->get('type') }}" @else type="text" @endif {{ $attributes->merge(['class' => 'form-control form-control-sm']) }} @if ($required) required @endif>
    </div>
@endif
