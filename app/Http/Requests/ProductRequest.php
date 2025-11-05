<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $routeParam = $this->route('product');
        $productId = is_object($routeParam) ? $routeParam->getKey() : $routeParam;

        return [
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('products', 'slug')->ignore($productId)],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'description' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string'],
            'highlight' => ['nullable', 'string'],
            'skin_concern' => ['nullable', 'string', 'max:255'],
            'skin_type' => ['nullable', 'string', 'max:255'],
            'remark' => ['nullable', 'string', 'max:500'],
            'country_of_origin' => ['nullable', 'string', 'max:120'],
            'sku' => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($productId)],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'manage_stock' => ['boolean'],
            'stock_status' => ['required', Rule::in(['in_stock','out_of_stock','on_backorder'])],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'dimensions' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:4096'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:4096'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
            'meta_title' => ['nullable', 'string', 'max:150'],
            'meta_description' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Normalize slug from provided slug or fallback to name
        $slugSource = $this->filled('slug') ? $this->input('slug') : $this->input('name');
        $slug = $slugSource ? Str::slug($slugSource) : $this->input('slug');

        // Map optional 'status' select to is_active boolean
        $status = $this->input('status');
        $isActiveFromStatus = null;
        if ($status !== null) {
            $isActiveFromStatus = $status === 'active';
        }

        $this->merge([
            'slug' => $slug,
            'manage_stock' => $this->has('manage_stock') ? $this->boolean('manage_stock') : true,
            'is_active' => ($isActiveFromStatus !== null)
                ? $isActiveFromStatus
                : ($this->has('is_active') ? $this->boolean('is_active') : true),
            'is_featured' => $this->has('is_featured') ? $this->boolean('is_featured') : false,
            'stock_quantity' => $this->input('stock_quantity', 0),
            'stock_status' => $this->input('stock_status', 'in_stock'),
            'sort_order' => $this->input('sort_order', 0),
        ]);
    }
}
