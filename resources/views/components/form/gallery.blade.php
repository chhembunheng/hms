@props(['label' => 'Gallery Images', 'name' => 'images', 'images' => null])

<div class="mb-3">
    <label class="form-label">{{ $label }}</label>
    
    <!-- Gallery Items -->
    <div id="gallery-container" class="row">
        @if($images && count($images) > 0)
            @foreach($images as $index => $item)
                <div class="col-md-6 mb-3 gallery-item" data-index="{{ $index }}">
                    <div class="card">
                        <img src="{{ asset($item['url'] ?? '') }}" class="card-img-top" style="max-height: 200px; object-fit: cover;" alt="Gallery image">
                        <div class="card-body">
                            <input type="hidden" name="{{ $name }}[{{ $index }}][url]" value="{{ $item['url'] ?? '' }}">
                            <input type="text" class="form-control form-control-sm mb-2" name="{{ $name }}[{{ $index }}][alt]" placeholder="Alt text" value="{{ $item['alt'] ?? '' }}">
                            <input type="text" class="form-control form-control-sm" name="{{ $name }}[{{ $index }}][label]" placeholder="Label" value="{{ $item['label'] ?? '' }}">
                            <button type="button" class="btn btn-sm btn-danger mt-2 remove-image" onclick="removeGalleryItem(this)">Remove</button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Add Image Button -->
    <div class="mt-3">
        <button type="button" class="btn btn-outline-primary" onclick="addGalleryImage('{{ $name }}')">
            <i class="fa-solid fa-plus me-2"></i>Add Gallery Image
        </button>
    </div>

    <!-- Hidden File Input for Upload -->
    <input type="file" id="gallery-file-input" class="d-none" accept="image/*" multiple onchange="handleGalleryUpload(event, '{{ $name }}')">
</div>

<script>
function addGalleryImage(fieldName) {
    document.getElementById('gallery-file-input').click();
}

function handleGalleryUpload(event, fieldName) {
    const files = event.target.files;
    const container = document.getElementById('gallery-container');
    let maxIndex = 0;

    // Get max index from existing items
    document.querySelectorAll(`#gallery-container .gallery-item`).forEach(item => {
        const index = parseInt(item.dataset.index) || 0;
        if (index >= maxIndex) maxIndex = index + 1;
    });

    // Process each file
    Array.from(files).forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const index = maxIndex + i;
            const div = document.createElement('div');
            div.className = 'col-md-6 mb-3 gallery-item';
            div.dataset.index = index;
            div.innerHTML = `
                <div class="card">
                    <img src="${e.target.result}" class="card-img-top" style="max-height: 200px; object-fit: cover;" alt="Gallery image">
                    <div class="card-body">
                        <input type="hidden" name="${fieldName}[${index}][url]" value="${e.target.result}">
                        <input type="text" class="form-control form-control-sm mb-2" name="${fieldName}[${index}][alt]" placeholder="Alt text" value="">
                        <input type="text" class="form-control form-control-sm" name="${fieldName}[${index}][label]" placeholder="Label" value="">
                        <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeGalleryItem(this)">Remove</button>
                    </div>
                </div>
            `;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });

    // Reset file input
    event.target.value = '';
}

function removeGalleryItem(button) {
    button.closest('.gallery-item').remove();
}
</script>
