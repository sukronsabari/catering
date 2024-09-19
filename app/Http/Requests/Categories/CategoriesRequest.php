<?php

namespace App\Http\Requests\Categories;

use Illuminate\Foundation\Http\FormRequest;

class CategoriesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2'],
            'description' => ['nullable', 'string'],
            'featured' => ['required', 'boolean'],
            'parent_id' => ['nullable', 'numeric', 'exists:categories,id'],
            'image' => [
                'nullable',
                'image',
                'mimes:png,jpg,jpeg,svg',
                'max:5000'
            ],
            'icon' => ['nullable', 'image', 'mimes:svg,png', 'max:5000']
        ];
    }
}
