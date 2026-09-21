<x-page-index 
    :title="__('Tours, Spa & Services Catalog')" 
    icon="compass">

    <x-slot:actions>
        <button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 rounded-2 px-3 fw-medium" onclick="openCreateServiceModal()">
            <i data-lucide="plus" style="width: 14px; height: 14px;"></i>
            <span>{{ __('New Service / Tour') }}</span>
        </button>
    </x-slot:actions>

    <x-slot:filters>
        <div class="d-flex flex-wrap gap-1 px-1 py-1">
            <a href="{{ route('services.catalog.index', ['category' => 'all']) }}" class="btn btn-sm {{ $category === 'all' ? 'btn-primary' : 'btn-light border' }} rounded-2 d-inline-flex align-items-center gap-1">
                <i data-lucide="layers" style="width: 13px; height: 13px;"></i>
                <span>{{ __('All Services') ?? 'All Services' }}</span>
            </a>
            <a href="{{ route('services.catalog.index', ['category' => 'tour']) }}" class="btn btn-sm {{ $category === 'tour' ? 'btn-primary' : 'btn-light border' }} rounded-2 d-inline-flex align-items-center gap-1">
                <i data-lucide="mountain-snow" style="width: 13px; height: 13px;"></i>
                <span>Angkor & Excursions</span>
            </a>
            <a href="{{ route('services.catalog.index', ['category' => 'transport']) }}" class="btn btn-sm {{ $category === 'transport' ? 'btn-primary' : 'btn-light border' }} rounded-2 d-inline-flex align-items-center gap-1">
                <i data-lucide="car" style="width: 13px; height: 13px;"></i>
                <span>Transport</span>
            </a>
            <a href="{{ route('services.catalog.index', ['category' => 'laundry']) }}" class="btn btn-sm {{ $category === 'laundry' ? 'btn-primary' : 'btn-light border' }} rounded-2 d-inline-flex align-items-center gap-1">
                <i data-lucide="shirt" style="width: 13px; height: 13px;"></i>
                <span>Laundry</span>
            </a>
            <a href="{{ route('services.catalog.index', ['category' => 'spa']) }}" class="btn btn-sm {{ $category === 'spa' ? 'btn-primary' : 'btn-light border' }} rounded-2 d-inline-flex align-items-center gap-1">
                <i data-lucide="sparkles" style="width: 13px; height: 13px;"></i>
                <span>Spa & Massage</span>
            </a>
            <a href="{{ route('services.catalog.index', ['category' => 'fnb']) }}" class="btn btn-sm {{ $category === 'fnb' ? 'btn-primary' : 'btn-light border' }} rounded-2 d-inline-flex align-items-center gap-1">
                <i data-lucide="utensils" style="width: 13px; height: 13px;"></i>
                <span>F&B / Breakfast</span>
            </a>
            <a href="{{ route('services.catalog.index', ['category' => 'minibar']) }}" class="btn btn-sm {{ $category === 'minibar' ? 'btn-primary' : 'btn-light border' }} rounded-2 d-inline-flex align-items-center gap-1">
                <i data-lucide="wine" style="width: 13px; height: 13px;"></i>
                <span>Minibar</span>
            </a>
        </div>
    </x-slot:filters>

    <!-- Services Catalog Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 datatables-custom">
            <thead class="table-light">
                <tr class="text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; color: #64748b;">
                    <th class="ps-3">{{ __('Code') }}</th>
                    <th>{{ __('Service Name (EN / KH)') }}</th>
                    <th>{{ __('Category') }}</th>
                    <th>{{ __('Billing Unit') }}</th>
                    <th class="text-end">{{ __('Price (USD / KHR)') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th class="text-end pe-3">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $svc)
                    <tr>
                        <td class="ps-3"><span class="badge bg-light text-dark font-monospace border rounded-2 px-2 py-1">{{ $svc->code }}</span></td>
                        <td>
                            <div class="fw-bold text-dark">{{ $svc->name_en }}</div>
                            @if($svc->name_kh)
                                <small class="text-muted d-block">{{ $svc->name_kh }}</small>
                            @endif
                            @if($svc->description)
                                <div class="text-muted small text-truncate" style="max-width: 380px;">{{ $svc->description }}</div>
                            @endif
                        </td>
                        <td>
                            @if($svc->category === 'tour')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1">Tour</span>
                            @elseif($svc->category === 'transport')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1">Transport</span>
                            @elseif($svc->category === 'laundry')
                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2 py-1">Laundry</span>
                            @elseif($svc->category === 'spa')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">Spa</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1">{{ ucfirst($svc->category) }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border rounded-pill px-2 py-1">{{ $svc->unit }}</span>
                        </td>
                        <td class="text-end">
                            <div class="fw-bold text-dark">${{ number_format($svc->price, 2) }}</div>
                            <small class="text-muted">{{ format_dual_currency($svc->price) }}</small>
                        </td>
                        <td>
                            @if($svc->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <div class="d-inline-flex align-items-center gap-1">
                                <button type="button" class="btn btn-sm btn-light border p-1 rounded-2 text-primary" onclick="editService({{ json_encode($svc) }})" title="Edit Service">
                                    <i data-lucide="pencil" style="width: 14px; height: 14px;"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-light border p-1 rounded-2 text-danger" onclick="deleteService({{ $svc->id }})" title="Delete Service">
                                    <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <div class="d-flex flex-column align-items-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                                    <i data-lucide="layers" style="width: 28px; height: 28px; stroke: #94a3b8;"></i>
                                </div>
                                <h6 class="fw-semibold text-secondary mb-1">No services found in this category.</h6>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-page-index>

<!-- Service Modal (Create / Edit) -->
<div class="modal fade" id="serviceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom py-2">
                <h6 class="modal-title fw-bold" id="serviceModalTitle">{{ __('New Service / Tour') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="serviceForm">
                @csrf
                <input type="hidden" name="id" id="serviceId">
                <div class="modal-body py-3">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Service Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="serviceCode" class="form-control form-control-sm font-monospace rounded-2" required placeholder="e.g. TOUR-SUNRISE">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="category" id="serviceCategory" class="form-select form-select-sm rounded-2" required>
                                <option value="tour">Angkor Tour & Excursion</option>
                                <option value="transport">Transport / Transfer</option>
                                <option value="laundry">Laundry & Dry Cleaning</option>
                                <option value="spa">Spa & Khmer Massage</option>
                                <option value="fnb">F&B / Breakfast</option>
                                <option value="minibar">Minibar Items</option>
                                <option value="other">Other Extra Service</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Billing Unit <span class="text-danger">*</span></label>
                            <input type="text" name="unit" id="serviceUnit" class="form-control form-control-sm rounded-2" required placeholder="per trip, per kg, per session">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Service Name (English) <span class="text-danger">*</span></label>
                            <input type="text" name="name_en" id="serviceNameEn" class="form-control form-control-sm rounded-2" required placeholder="e.g. Angkor Wat Sunrise Tour">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Service Name (Khmer)</label>
                            <input type="text" name="name_kh" id="serviceNameKh" class="form-control form-control-sm rounded-2" placeholder="e.g. ដំណើរទស្សនាថ្ងៃរះអង្គរវត្ត">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Price (USD) <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text rounded-start-2">$</span>
                                <input type="number" step="0.01" name="price" id="servicePrice" class="form-control form-control-sm rounded-end-2" required placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-8 d-flex align-items-center mt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="serviceIsActive" value="1" checked>
                                <label class="form-check-label fw-semibold small" for="serviceIsActive">Active & Available for Booking</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold">Description / Inclusions</label>
                            <textarea name="description" id="serviceDescription" class="form-control form-control-sm rounded-2" rows="3" placeholder="Includes cold towels, bottled water, English-speaking remork driver..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-2" data-bs-dismiss="modal">{{ __('global.cancel') }}</button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-2">{{ __('form.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openCreateServiceModal() {
        $('#serviceForm')[0].reset();
        $('#serviceId').val('');
        $('#serviceIsActive').prop('checked', true);
        $('#serviceModalTitle').text('{{ __("New Service / Tour") }}');
        new bootstrap.Modal(document.getElementById('serviceModal')).show();
    }

    function editService(svc) {
        $('#serviceId').val(svc.id);
        $('#serviceCode').val(svc.code);
        $('#serviceCategory').val(svc.category);
        $('#serviceUnit').val(svc.unit);
        $('#serviceNameEn').val(svc.name_en);
        $('#serviceNameKh').val(svc.name_kh || '');
        $('#servicePrice').val(svc.price);
        $('#serviceDescription').val(svc.description || '');
        $('#serviceIsActive').prop('checked', !!svc.is_active);
        $('#serviceModalTitle').text('Edit Service / Tour');
        new bootstrap.Modal(document.getElementById('serviceModal')).show();
    }

    $(document).ready(function() {
        $('#serviceForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('services.catalog.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    let msg = 'Failed to save service.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire('Error', msg, 'error');
                }
            });
        });
    });

    function deleteService(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Delete this service catalog item?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/services/catalog/${id}/delete`,
                    method: "DELETE",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        Swal.fire('Deleted!', response.message, 'success').then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to delete service', 'error');
                    }
                });
            }
        });
    }
</script>
@endpush
