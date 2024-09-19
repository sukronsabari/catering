<?php

namespace App\Http\Requests\Sliders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SlidersRequest extends FormRequest
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
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'subtitle' => ['required', 'string', 'min:3', 'max:255'],
            'starting_price' => ['required', 'numeric', 'min:0'],
            'position' => ['required', 'numeric', 'min:1'],
            'is_active' => ['required', 'boolean'],
            'url' => ['required', 'string', 'min:3', 'max:255'],
            'image' => [
                $this->method() ==='POST' ? 'required' : 'nullable',
                'image',
                'mimes:png,jpg,jpeg',
                'max:5000'
            ],
        ];
    }
}
