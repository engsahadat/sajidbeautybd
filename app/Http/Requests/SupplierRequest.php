<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
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
        $supplierId = $this->route('supplier') ?? null;

        return [
            'product_id' => ['required', 'exists:products,id'],
            'vendor_id' => ['required', 'exists:vendors,id'],
            'supplier_sku' => ['nullable', 'string', 'max:50'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'minimum_order_quantity' => ['required', 'integer', 'min:1'],
            'lead_time_days' => ['required', 'integer', 'min:0'],
            'is_primary' => ['boolean'],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'Product is required.',
            'product_id.exists' => 'Selected product does not exist.',
            'vendor_id.required' => 'Vendor is required.',
            'vendor_id.exists' => 'Selected vendor does not exist.',
            'supplier_sku.max' => 'Supplier SKU cannot exceed 50 characters.',
            'cost_price.required' => 'Cost price is required.',
            'cost_price.numeric' => 'Cost price must be a valid number.',
            'cost_price.min' => 'Cost price cannot be negative.',
            'minimum_order_quantity.required' => 'Minimum order quantity is required.',
            'minimum_order_quantity.integer' => 'Minimum order quantity must be a whole number.',
            'minimum_order_quantity.min' => 'Minimum order quantity must be at least 1.',
            'lead_time_days.required' => 'Lead time in days is required.',
            'lead_time_days.integer' => 'Lead time must be a whole number.',
            'lead_time_days.min' => 'Lead time cannot be negative.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default values
        $this->merge([
            'is_primary' => $this->boolean('is_primary', false),
        ]);
    }
}