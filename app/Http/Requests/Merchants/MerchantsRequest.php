<?php

namespace App\Http\Requests\Merchants;

use App\Models\User;
use App\Models\Merchant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MerchantsRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $socialLinks = array_map(function ($link) {
            $link['platform'] = strtolower($link['platform']);
            return $link;
        }, $this->input('social_links', []));

        $this->merge([
            'social_links' => $socialLinks,
            'user_id' => (int) $this->input('user_id')
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(Request $request): array
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                $this->method() === 'POST'
                    ? Rule::unique(Merchant::class, 'user_id')
                    : Rule::unique('merchants', 'user_id')->ignore($this->merchant->user_id, 'user_id'),
            ],
            'is_official' => ['required', 'boolean'],
            'banner_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:5000'],
            'description' => ['nullable', 'string', 'min:5'],
            'phone' => [
                'required',
                'regex:/^(\+\d{1,3}\s?)?(\(?\d{1,4}\)?[\s.-]?)?\d{1,4}([\s.-]?\d{1,4}){1,4}$/',
            ],
            'social_links' => ['nullable', 'array'],
            'social_links.*.platform' => ['nullable', 'string', 'in:youtube,instagram,twitter,facebook'],
            'social_links.*.link' => ['nullable', 'string', 'url'],

            // Merchant address
            'address_detail' => ['required', 'string', 'min:5'],
            'postal_code' => ['required', 'string', 'min:5'],
            'province' => ['required', 'integer', 'exists:indonesia_provinces,id'],
            'city' => ['required', 'integer', 'exists:indonesia_cities,id'],
            'district' => ['required', 'integer', 'exists:indonesia_districts,id'],
            'village' => ['required', 'integer', 'exists:indonesia_villages,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'social_links.*.platform.in' => 'Provide platform one of the value: youtube, instagram, twitter or facebook',
        ];
    }
}

