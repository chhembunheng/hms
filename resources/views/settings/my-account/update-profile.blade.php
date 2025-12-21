<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="d-flex justify-content-between p-3 pb-0">
            <h5>{{ $title }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body pt-0">
            <form id="update-profile-form" action="{{ route('settings.my-account.update-profile') }}" method="POST" enctype="multipart/form-data" class="ajax-form-modal">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="avatar" class="form-label">Profile Picture</label>

                            <!-- Current Avatar Preview -->
                            <div class="mb-3 text-center">
                                <div class="d-inline-block position-relative">
                                    @if ($user->avatar)
                                        <img src="{{ asset($user->avatar) }}" class="rounded-pill" style="width: 100px; height: 100px; object-fit: cover;" alt="Current Profile Picture">
                                    @else
                                        <img src="{{ asset('assets/images/default/male-avatar.jpg') }}" class="rounded-pill" style="width: 100px; height: 100px; object-fit: cover;" alt="Default Profile Picture">
                                    @endif
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <i class="fa-solid fa-camera text-white" style="font-size: 24px; text-shadow: 0 0 3px rgba(0,0,0,0.7);"></i>
                                    </div>
                                </div>
                                <p class="text-muted mt-2 small">Click "Choose Image" to change your profile picture</p>
                            </div>

                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar" accept="image/*" style="display: none;">
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-outline-primary" id="select-image-btn">
                                    <i class="fa-solid fa-image fa-fw"></i> Choose Image
                                </button>
                                <small class="text-muted">Accepted formats: JPG, PNG, GIF. Max size: 50MB</small>
                            </div>
                            @error('avatar')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Image Preview and Crop Area -->
                <div class="row mb-3" id="image-preview-container" style="display: none;">
                    <div class="col-md-12">
                        <div class="text-center">
                            <div class="mb-3">
                                <img id="image-preview" src="" alt="Preview" style="max-width: 100%; max-height: 300px;">
                            </div>
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-success" id="crop-save-btn">
                                    <i class="fa-solid fa-crop fa-fw"></i> Save Crop
                                </button>
                                <button type="button" class="btn btn-secondary" id="crop-cancel-btn">
                                    <i class="fa-solid fa-times fa-fw"></i> Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="first_name_en" class="form-label">First Name (English) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('first_name.en') is-invalid @enderror" id="first_name_en" name="first_name[en]" value="{{ old('first_name.en', $user->translations->where('locale', 'en')->first()->first_name ?? '') }}" required>
                            @error('first_name.en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="last_name_en" class="form-label">Last Name (English) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('last_name.en') is-invalid @enderror" id="last_name_en" name="last_name[en]" value="{{ old('last_name.en', $user->translations->where('locale', 'en')->first()->last_name ?? '') }}" required>
                            @error('last_name.en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="first_name_km" class="form-label">First Name (Khmer) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('first_name.km') is-invalid @enderror" id="first_name_km" name="first_name[km]" value="{{ old('first_name.km', $user->translations->where('locale', 'km')->first()->first_name ?? '') }}" required>
                            @error('first_name.km')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="last_name_km" class="form-label">Last Name (Khmer) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('last_name.km') is-invalid @enderror" id="last_name_km" name="last_name[km]" value="{{ old('last_name.km', $user->translations->where('locale', 'km')->first()->last_name ?? '') }}" required>
                            @error('last_name.km')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cropper.js CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/vendor/cropperjs/cropper.min.css') }}?v={{ config('init.layout_version') }}">

<script src="{{ asset('assets/js/vendor/cropperjs/cropper.min.js') }}?v={{ config('init.layout_version') }}"></script>

<script>
$(document).ready(function() {
    let cropper = null;
    let croppedBlob = null;
    let isAvatarEditMode = window.location.hash === '#avatar-edit';

    // Check if modal was opened for avatar editing
    if (isAvatarEditMode) {
        // Auto-trigger image selection for avatar editing
        setTimeout(function() {
            $('#select-image-btn').click();
        }, 500); // Small delay to ensure modal is fully loaded
    }

    // Handle image selection
    $('#select-image-btn').on('click', function() {
        $('#avatar').click();
    });

    // Handle file input change
    $('#avatar').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                error('Please select a valid image file (JPG, PNG, or GIF).');
                return;
            }

            // Validate file size (50MB)
            if (file.size > 50 * 1024 * 1024) {
                error('File size must be less than 50MB.');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result);
                $('#image-preview-container').show();

                // Hide the form fields when in avatar editing mode
                if (isAvatarEditMode) {
                    $('.form-group:not(:has(#image-preview-container))').hide();
                    $('.modal-footer').hide();
                    $('.modal-header h5').text('Edit Profile Picture');
                }

                // Initialize cropper
                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper($('#image-preview')[0], {
                    aspectRatio: 1, // Square crop for profile pictures
                    viewMode: 1,
                    dragMode: 'move',
                    responsive: true,
                    restore: false,
                    checkCrossOrigin: false,
                    checkOrientation: false,
                    modal: true,
                    guides: true,
                    center: true,
                    highlight: false,
                    background: false,
                    autoCrop: true,
                    autoCropArea: 0.8,
                    scalable: true,
                    zoomable: true,
                    zoomOnTouch: true,
                    zoomOnWheel: true,
                    wheelZoomRatio: 0.1,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                });
            };
            reader.readAsDataURL(file);
        }
    });

    // Handle crop save
    $('#crop-save-btn').on('click', function() {
        if (cropper) {
            cropper.getCroppedCanvas({
                width: 300,
                height: 300,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            }).toBlob(function(blob) {
                croppedBlob = blob;

                // Create a new file from the blob
                const croppedFile = new File([blob], 'cropped-avatar.jpg', { type: 'image/jpeg' });

                // Create a DataTransfer to set the file input
                const dt = new DataTransfer();
                dt.items.add(croppedFile);
                $('#avatar')[0].files = dt.files;

                // Submit the form automatically
                $('#update-profile-form').submit();

            }, 'image/jpeg', 0.9);
        }
    });

    // Handle crop cancel
    $('#crop-cancel-btn').on('click', function() {
        $('#image-preview-container').hide();
        $('#avatar').val('');
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }

        // Close modal if in avatar editing mode
        if (isAvatarEditMode) {
            $('.modal').modal('hide');
        }
    });
});
</script>
