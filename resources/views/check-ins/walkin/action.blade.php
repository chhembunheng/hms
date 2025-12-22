<div class="d-inline-flex dropdown ms-2">
    <a href="#" class="text-body" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-bars"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end">
        @foreach ($actions as $action)
            @if ($action['action'] === 'cancel')
                <a href="#" class="dropdown-item" onclick="cancelCheckIn(event, {{ $row->id }}, '{{ $row->booking_number }}')">
                    <i class="fa-light {{ $action['icon'] }} me-2"></i> {{ $action->translations->firstWhere('locale', app()->getLocale())->name }}
                </a>
                @continue
            @endif
            @if ($action['target'] === 'self')
                <a href="{{ route($action['action_route'], ['id' => $row->id]) }}" class="dropdown-item">
                    <i class="fa-light {{ $action['icon'] }} me-2"></i> {{ $action->translations->firstWhere('locale', app()->getLocale())->name }}
                </a>
            @endif
        @endforeach
    </div>
</div>

<script>
function cancelCheckIn(event, id, bookingNumber) {
    event.preventDefault();
    const confirmText = `Are you sure you want to cancel check-in "${bookingNumber}"? This action cannot be undone.`;
    swalInit.fire({
        title: '{{ __("messages.are_you_sure") }}',
        text: confirmText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '{{ __("messages.yes_delete") }}',
        cancelButtonText: '{{ __("messages.no_cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create a form to submit POST request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ url("checkin/walkin") }}/' + id + '/cancel';

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

