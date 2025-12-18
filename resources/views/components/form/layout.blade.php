@props(['form'])
<form action="{{ route(Route::currentRouteName(), $form?->id) }}" method="POST" enctype="multipart/form-data" validate>
    <div class="alert alert-purple d-flex align-items-center" role="alert">
        <i class="fa-light fa-circle-info"></i>
        <span class="fw-semibold ms-2">{!! __('form.instruction') !!}</span>
    </div>
    @csrf
    {{ $slot }}
</form>
