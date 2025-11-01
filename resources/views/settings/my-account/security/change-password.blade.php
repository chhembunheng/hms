<div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="d-flex justify-content-between p-3 pb-0">
            <h5>{{ $title }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body pt-0">
            <form action="{{ $action ?? '' }}" method="POST" class="ajax-form-modal">
                @csrf
                <p class="m-0 text-muted">Update your account password to ensure you are using a long, random password to stay secure.</p>
                <div class="mb-3 mt-3">
                    <label for="current-password" class="form-label required">Current Password</label>
                    <input type="password" id="current-password" class="form-control form-control-sm" name="current_password" required placeholder="Enter current password">
                </div>
                <div class="mb-3">
                    <label for="new-password" class="form-label required">New Password</label>
                    <input type="password" id="new-password" class="form-control form-control-sm" name="new_password" required placeholder="Enter new password">
                </div>
                <div class="mb-3">
                    <label for="confirm-password" class="form-label required">Confirm New Password</label>
                    <input type="password" id="confirm-password" class="form-control form-control-sm" name="confirm_password" required placeholder="Confirm new password">
                </div>
                <div class="mb-3">
                    <label for="confirm-password" class="form-label required">Confirm New Password</label>
                    <select multiple data-placeholder="Select a state..." class="form-control select2 form-control-sm" tabindex="-1" aria-hidden="true" required name="social_media[]">
                        <option value="slack" data-icon="slack-logo">Slack</option>
                        <option value="instagram" data-icon="instagram-logo">Instagram</option>
                        <option value="telegram" data-icon="telegram-logo">Telegram</option>
                        <option value="whatsapp" data-icon="whatsapp-logo">Whatsapp</option>
                        <option value="twitter" data-icon="twitter-logo">Twitter</option>
                    </select>
                </div>
                <div class="justify-content-end d-flex gap-2">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>
