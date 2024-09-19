<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ImageOrString implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!(is_string($value) || $value instanceof \Illuminate\Http\UploadedFile)) {
            $fail("The $attribute must be a valid image file or an existing image path.");
            return;
        }

        if (is_string($value)) {
            if (!filter_var($value, FILTER_VALIDATE_URL) && !file_exists(public_path($value))) {
                $fail("The $attribute must be a valid URL or an existing file path.");
            }
        }

        if ($value instanceof \Illuminate\Http\UploadedFile) {
            $mimeType = $value->getMimeType();
            if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])) {
                $fail("The $attribute must be a valid image file (jpeg, png, jpg, gif).");
            }
            if ($value->getSize() > 5000 * 1024) { // max 5000 KB
                $fail("The $attribute must not be greater than 5MB.");
            }
        }
    }
}
