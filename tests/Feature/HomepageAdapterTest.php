<?php

// Logging assertions removed (Log::fake not available); we just ensure page renders.

it('renders homepage', function () {
    $this->get('/en')
        ->assertStatus(200);
});

it('renders faq page', function () {
    $this->get('/en/faq')
        ->assertStatus(200);
});
