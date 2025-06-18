<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalesOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'products.required' => 'At least one product is required.',
            'products.min' => 'At least one product must be selected.',
            'products.*.id.required' => 'Product selection is required.',
            'products.*.id.exists' => 'Selected product does not exist.',
            'products.*.quantity.required' => 'Product quantity is required.',
            'products.*.quantity.integer' => 'Product quantity must be a whole number.',
            'products.*.quantity.min' => 'Product quantity must be at least 1.',
        ];
    }
}