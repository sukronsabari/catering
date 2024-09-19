<?php

namespace App\Http\Requests\Products;

use App\Rules\ImageOrString;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(Request $request): array
    {
        // dd($request->all());
        return [
            'name' => ['required', 'string', 'min:3'],
            'description' => ['nullable', 'string', 'min:5'],
            'price' => ['required_without:skus', 'numeric', 'min:100'],
            'stock' => ['required_without:skus', 'integer', 'min:1'],
            'weight' => ['required_without:skus', 'numeric', 'min:1'],
            'sku' => ['nullable', 'string', 'min:1'],
            'is_active' => ['required_without:skus', 'boolean'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'merchant_id' => ['required', 'integer', 'exists:merchants,id'],
            'images' => ['required', 'array', 'min:1', 'max:9'],
            'images.*.file' => [strtoupper($this->method() === 'POST') ? 'required' : 'nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5000'],
            'images.*.path' => ['nullable', 'string'],
            'images.*.is_main' => ['required', 'boolean'],

            // 'attributes_values' => ['nullable', 'array', 'max:2'],
            // 'attributes_values.*.name' => ['nullable', 'string'],
            // 'attributes_values.*.options' => ['nullable', 'array'],
            // 'attributes_values.*.options.*' => ['nullable','string'],

            'skus' => ['nullable', 'array', 'min:1'],
            'skus.*.attribute_value' => ['required_with:skus', 'array'],
            'skus.*.attribute_value.*.attribute' => ['required_with:skus', 'string'],
            'skus.*.attribute_value.*.value' => ['required_with:skus', 'string'],
            'skus.*.price' => ['required_with:skus', 'numeric', 'min:100'],
            'skus.*.stock' => ['required_with:skus', 'integer', 'min:1'],
            'skus.*.weight' => ['required_with:skus', 'numeric', 'min:1'],
            'skus.*.sku' => ['nullable', 'string', 'min:1'],
            'skus.*.is_active' => ['required_with:skus', 'boolean'],
            'skus.*.is_default' => ['required_with:skus', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id' => 'Please select the category',
            'merchant_id' => 'Please select the merchant',
            'images.*.*' => 'Image required',
            'price.required_without' => 'Price is required when product variant is not present',
            'stock.required_without' => 'Stock is required when product variant is not present',
            'weight.required_without' => 'Weight is required when product variant is not present',
            'is_active.required_without' => 'Status is required when product variant is not present',
            'sku.required_without' => 'SKU is required when product variant is not present',
        ];
    }
}
