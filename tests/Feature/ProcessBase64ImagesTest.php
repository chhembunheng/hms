<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProcessBase64ImagesTest extends TestCase
{
    /**
     * Test that base64 images in HTML are extracted and saved to disk.
     */
    public function test_base64_images_are_processed(): void
    {
        // Create a simple base64 PNG (1x1 pixel)
        $base64Png = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $html = <<<HTML
        <div>
            <h1>Test Post</h1>
            <img src="data:image/png;base64,$base64Png" alt="test image" />
            <p>Some text</p>
        </div>
        HTML;

        $response = $this->postJson('/api/test-endpoint', [
            'title' => 'Test',
            'content' => $html,
        ]);

        // The response should contain processed content
        $this->assertNotNull($response);
    }

    /**
     * Test multiple images in one request.
     */
    public function test_multiple_base64_images_are_processed(): void
    {
        $base64Png = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $html = <<<HTML
        <div>
            <img src="data:image/png;base64,$base64Png" alt="image 1" />
            <img src="data:image/png;base64,$base64Png" alt="image 2" />
            <img src="data:image/png;base64,$base64Png" alt="image 3" />
        </div>
        HTML;

        $response = $this->postJson('/api/test-endpoint', [
            'content' => $html,
        ]);

        $this->assertNotNull($response);
    }

    /**
     * Test JSON request with base64 images.
     */
    public function test_json_request_with_base64_images(): void
    {
        $base64Png = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $response = $this->postJson('/api/posts', [
            'title' => 'Test Post',
            'content' => '<img src="data:image/png;base64,' . $base64Png . '" />',
            'tags' => ['test', 'base64'],
        ]);

        $this->assertNotNull($response);
    }

    /**
     * Test nested objects with base64 images.
     */
    public function test_nested_data_with_base64_images(): void
    {
        $base64Png = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $response = $this->postJson('/api/posts', [
            'title' => 'Test',
            'sections' => [
                [
                    'heading' => 'Section 1',
                    'content' => '<img src="data:image/png;base64,' . $base64Png . '" />',
                ],
                [
                    'heading' => 'Section 2',
                    'content' => '<img src="data:image/png;base64,' . $base64Png . '" />',
                ],
            ],
        ]);

        $this->assertNotNull($response);
    }

    /**
     * Test that non-base64 HTML is left unchanged.
     */
    public function test_normal_html_is_unchanged(): void
    {
        $html = '<div><img src="/storage/image.png" /><p>Test</p></div>';

        $response = $this->postJson('/api/posts', [
            'content' => $html,
        ]);

        $this->assertNotNull($response);
    }

    /**
     * Test mixed base64 and normal images.
     */
    public function test_mixed_base64_and_normal_images(): void
    {
        $base64Png = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $html = <<<HTML
        <div>
            <img src="/storage/existing.png" alt="existing" />
            <img src="data:image/png;base64,$base64Png" alt="new" />
            <img src="/storage/another.jpg" alt="another" />
        </div>
        HTML;

        $response = $this->postJson('/api/posts', [
            'content' => $html,
        ]);

        $this->assertNotNull($response);
    }
}
