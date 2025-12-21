<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="d-flex justify-content-between p-3 pb-0">
            <h5>{{ $title }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body pt-0">
            <form id="update-profile-form" action="/settings/my-account/update-profile" method="POST" enctype="multipart/form-data" class="ajax-form-modal">
                @csrf
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
                    <button type="button" class="btn btn-primary btn-sm" onclick="submitUpdateProfileForm()">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function submitUpdateProfileForm() {
    const form = document.getElementById('update-profile-form');
    const formData = new FormData(form);

    // Show loading state
    const submitBtn = form.querySelector('button[type="button"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Updating...';

    $.ajax({
        url: "{{ route('settings.my-account.update-profile') }}",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Success response:', response);
            if (response.status === 'success') {
                $('#modal-remote').modal('hide');
                success(response.message);
                // Reload the page to show updated info
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                error(response.message || 'Update failed');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', xhr, status, error);
            let message = 'An error occurred while updating profile';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            } else if (xhr.responseText) {
                try {
                    const jsonResponse = JSON.parse(xhr.responseText);
                    if (jsonResponse.message) {
                        message = jsonResponse.message;
                    }
                } catch (e) {
                    message = xhr.responseText;
                }
            }
            error(message);
        },
        complete: function() {
            // Re-enable button
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
}
</script>

