<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
        $product = $this->route('product');

        return [
            'name' => [
                'sometimes', 'string', 'max:255',
                Rule::unique('products', 'name')->ignore($product?->id)
            ],
            'description' => ['sometimes', 'string', 'max:1000'],
            'price' => ['sometimes', 'numeric', 'decimal:0,2', 'min:0'],
            'currency_id' => ['sometimes', 'integer', 'exists:currencies,id'],
            'tax_cost' => ['sometimes', 'numeric', 'decimal:0,2', 'min:0'],
            'manufacturing_cost' => ['sometimes', 'numeric', 'decimal:0,2', 'min:0'],
        ];
    }
}
