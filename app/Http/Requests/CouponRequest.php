<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends FormRequest
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
        $couponId = $this->route('coupon') ?? null;

        return [
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('coupons', 'code')->ignore($couponId)
            ],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'type' => ['required', 'in:fixed,percentage'],
            'value' => ['required', 'numeric', 'min:0.01'],
            'minimum_amount' => ['nullable', 'numeric', 'min:0'],
            'maximum_discount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'usage_limit_per_customer' => ['required', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Coupon code is required.',
            'code.unique' => 'This coupon code is already taken.',
            'code.max' => 'Coupon code cannot exceed 20 characters.',
            'name.required' => 'Coupon name is required.',
            'name.max' => 'Coupon name cannot exceed 100 characters.',
            'description.max' => 'Description cannot exceed 500 characters.',
            'type.required' => 'Coupon type is required.',
            'type.in' => 'Coupon type must be either fixed or percentage.',
            'value.required' => 'Coupon value is required.',
            'value.numeric' => 'Coupon value must be a valid number.',
            'value.min' => 'Coupon value must be at least 0.01.',
            'minimum_amount.numeric' => 'Minimum amount must be a valid number.',
            'minimum_amount.min' => 'Minimum amount cannot be negative.',
            'maximum_discount.numeric' => 'Maximum discount must be a valid number.',
            'maximum_discount.min' => 'Maximum discount cannot be negative.',
            'usage_limit.integer' => 'Usage limit must be a whole number.',
            'usage_limit.min' => 'Usage limit must be at least 1.',
            'usage_limit_per_customer.required' => 'Usage limit per customer is required.',
            'usage_limit_per_customer.integer' => 'Usage limit per customer must be a whole number.',
            'usage_limit_per_customer.min' => 'Usage limit per customer must be at least 1.',
            'starts_at.date' => 'Start date must be a valid date.',
            'expires_at.date' => 'Expiry date must be a valid date.',
            'expires_at.after_or_equal' => 'Expiry date must be after or equal to start date.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either active or inactive.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default values
        $this->merge([
            'usage_limit_per_customer' => $this->input('usage_limit_per_customer', 1),
            'status' => $this->input('status', 'active')
        ]);
    }
}