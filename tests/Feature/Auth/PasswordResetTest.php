<?php

// Password reset is disabled - admin resets passwords only
// Skipping password reset tests

test('reset password link screen is disabled', function () {
    $response = $this->get('/forgot-password');
    $response->assertStatus(404);
});

test('reset password link request is disabled', function () {
    $response = $this->post('/forgot-password', [
        'email' => 'test@example.com',
    ]);
    $response->assertStatus(404);
});

test('reset password screen is disabled', function () {
    $response = $this->get('/reset-password/token123');
    $response->assertStatus(404);
});
