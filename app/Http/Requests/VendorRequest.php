<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendorRequest extends FormRequest
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
        $vendorId = $this->route('vendor') ?? null;

        return [
            'name' => ['required', 'string', 'max:150'],
            'company' => ['nullable', 'string', 'max:150'],
            'contact_name' => ['nullable', 'string', 'max:100'],
            'email' => [
                'nullable',
                'email',
                'max:150',
                Rule::unique('vendors', 'email')->ignore($vendorId)
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'address_line_1' => ['nullable', 'string', 'max:191'],
            'address_line_2' => ['nullable', 'string', 'max:191'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'size:2'],
            'website' => ['nullable', 'url', 'max:191'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string']
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Vendor name is required.',
            'name.max' => 'Vendor name cannot exceed 150 characters.',
            'company.max' => 'Company name cannot exceed 150 characters.',
            'contact_name.max' => 'Contact name cannot exceed 100 characters.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already taken.',
            'email.max' => 'Email cannot exceed 150 characters.',
            'phone.max' => 'Phone number cannot exceed 50 characters.',
            'address_line_1.max' => 'Address line 1 cannot exceed 191 characters.',
            'address_line_2.max' => 'Address line 2 cannot exceed 191 characters.',
            'city.max' => 'City name cannot exceed 100 characters.',
            'state.max' => 'State name cannot exceed 100 characters.',
            'postal_code.max' => 'Postal code cannot exceed 20 characters.',
            'country.size' => 'Country code must be exactly 2 characters.',
            'website.url' => 'Please enter a valid website URL.',
            'website.max' => 'Website URL cannot exceed 191 characters.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either active or inactive.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default values
        $this->merge([
            'status' => $this->input('status', 'active'),
            'country' => $this->input('country', 'BD')
        ]);
    }
}