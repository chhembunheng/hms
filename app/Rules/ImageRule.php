<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class ImageRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value instanceof UploadedFile) {
            if (! $value->isValid()) {
                $fail("The $attribute is not a valid uploaded file.");
                return;
            }
            $allowed = ['jpeg', 'jpg', 'png', 'gif', 'svg', 'webp'];
            if (! in_array(strtolower($value->extension()), $allowed)) {
                $fail("The $attribute must be an image of type: " . implode(', ', $allowed));
            }
            return;
        }
        if (is_string($value)) {
            $pattern = '/^data:image\/(png|jpe?g|gif|svg\+xml|webp);base64,([A-Za-z0-9+\/=]+)$/';
            if (! preg_match($pattern, $value)) {
                $fail("The $attribute must be an uploaded image file or a valid Base64 encoded image.");
            }
            return;
        }
        $fail("The $attribute must be an uploaded image file or a valid Base64 encoded image.");
    }
}
