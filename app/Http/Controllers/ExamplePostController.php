<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Example Controller showing how to use ProcessBase64Images Middleware
 * 
 * Replace 'Post' with your actual model class.
 * This is a reference example - not tied to a specific model.
 * 
 * When this middleware is registered, any base64 images in the request
 * will be automatically processed before reaching these controller methods.
 */
class ExamplePostController extends Controller
{
    /**
     * Store a new post with HTML content containing base64 images.
     * 
     * The middleware automatically:
     * - Finds <img src="data:image/...;base64,..."> tags
     * - Saves images to storage/app/public/editor-images/
     * - Replaces base64 data with /storage/editor-images/{filename}
     * 
     * Example request:
     * POST /api/posts
     * {
     *   "title": "My Post",
     *   "content": "<img src=\"data:image/png;base64,iVBORw0KGgo...\" />"
     * }
     * 
     * By the time this method runs, the content will have been processed:
     * {
     *   "title": "My Post",
     *   "content": "<img src=\"/storage/editor-images/img_1234567890.png\" />"
     * }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string', // Can contain HTML with base64 images
            'excerpt' => 'nullable|string',
        ]);

        // At this point, base64 images have been extracted and saved
        // The 'content' field now contains real file URLs
        // Replace 'Post' with your actual model
        // $post = Post::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $validated,
        ], 201);
    }

    /**
     * Update an existing post with new HTML content.
     * 
     * Same middleware processing applies to PUT/PATCH requests.
     */
    public function update(Request $request, string $postId)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string', // Processed if present
            'excerpt' => 'nullable|string',
        ]);

        // Base64 images have been processed by middleware
        // $post = Post::findOrFail($postId);
        // $post->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully',
            'data' => $validated,
        ]);
    }

    /**
     * Example with nested content (sections).
     * 
     * The middleware recursively processes nested arrays and objects.
     * 
     * Request:
     * {
     *   "title": "Multi-section Post",
     *   "sections": [
     *     {
     *       "heading": "Section 1",
     *       "content": "<img src=\"data:image/png;base64,...\" />"
     *     },
     *     {
     *       "heading": "Section 2",
     *       "content": "<img src=\"data:image/jpeg;base64,...\" />"
     *     }
     *   ]
     * }
     * 
     * All base64 images in nested sections are processed.
     */
    public function storeWithSections(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'sections' => 'required|array',
            'sections.*.heading' => 'required|string',
            'sections.*.content' => 'required|string', // Processed by middleware
        ]);

        // All nested content has been processed
        // $post = Post::create($validated);

        return response()->json([
            'success' => true,
            'data' => $validated,
        ], 201);
    }
}

/**
 * Example Frontend Form (JavaScript/Vue)
 * =====================================
 * 
 * This shows how a text editor would typically send data.
 */

/*
<template>
  <form @submit.prevent="submitPost">
    <input v-model="form.title" placeholder="Post Title" />
    
    <div id="editor" contenteditable="true" class="editor">
      <!-- Users paste/upload images here - they become base64 -->
    </div>
    
    <button type="submit">Publish</button>
  </form>
</template>

<script setup>
import { ref } from 'vue'

const form = ref({
  title: '',
})

const submitPost = async () => {
  // Get HTML from editor (may contain base64 images)
  const editorContent = document.getElementById('editor').innerHTML
  
  // Send to server
  const response = await fetch('/api/posts', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
    },
    body: JSON.stringify({
      title: form.value.title,
      content: editorContent // Contains base64 images
    })
  })
  
  const data = await response.json()
  if (data.success) {
    // Post created! Images were automatically processed
    console.log('Post created:', data.post)
  }
}
</script>
*/

/**
 * Example Route Configuration
 * ===========================
 */

// Option 1: Apply to specific routes
/*
Route::prefix('api')->group(function () {
    Route::post('/posts', [ExamplePostController::class, 'store'])
        ->middleware('process-base64');
    Route::put('/posts/{post}', [ExamplePostController::class, 'update'])
        ->middleware('process-base64');
});
*/

// Option 2: Apply to all web routes (in bootstrap/app.php)
/*
->withMiddleware(function (Middleware $middleware): void {
    $middleware->group('web', [
        // ... other middleware
        \App\Http\Middleware\ProcessBase64Images::class,
    ]);
})
*/

// Option 3: Use API middleware group
/*
->withMiddleware(function (Middleware $middleware): void {
    $middleware->group('api', [
        // ... other middleware
        \App\Http\Middleware\ProcessBase64Images::class,
    ]);
})
*/
