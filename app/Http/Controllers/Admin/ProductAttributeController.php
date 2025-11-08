<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductAttributeController extends Controller
{
    /**
     * Display a listing of product attributes.
     */
    public function index(Request $request, Product $product)
    {
        $query = ProductAttribute::where('product_id', $product->id);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('attribute_name', 'like', "%{$search}%")
                  ->orWhere('attribute_value', 'like', "%{$search}%")
                  ->orWhere('attribute_group', 'like', "%{$search}%");
            });
        }

        $attributes = $query->orderBy('attribute_group')
                          ->orderBy('sort_order')
                          ->paginate(20);

        return view('admin.product-attribute.index', compact('product', 'attributes'));
    }

    /**
     * Show the form for creating a new attribute.
     */
    public function create(Product $product)
    {
        return view('admin.product-attribute.create', compact('product'));
    }

    /**
     * Store a newly created attribute.
     */
    public function store(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'attribute_name' => 'required|string|max:100',
            'attribute_value' => 'required|string',
            'attribute_group' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'attribute_name.required' => 'Attribute name is required.',
            'attribute_name.max' => 'Attribute name must not exceed 100 characters.',
            'attribute_value.required' => 'Attribute value is required.',
            'attribute_group.max' => 'Attribute group must not exceed 50 characters.',
            'sort_order.integer' => 'Sort order must be a number.',
            'sort_order.min' => 'Sort order must be at least 0.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $attribute = new ProductAttribute();
        $attribute->product_id = $product->id;
        $attribute->attribute_name = $request->attribute_name;
        $attribute->attribute_value = $request->attribute_value;
        $attribute->attribute_group = $request->attribute_group;
        $attribute->sort_order = $request->sort_order ?? 0;
        $attribute->save();

        return response()->json([
            'success' => true,
            'message' => 'Product attribute created successfully.',
            'redirect' => route('products.attributes.index', $product->id)
        ]);
    }

    /**
     * Display the specified attribute.
     */
    public function show(Product $product, ProductAttribute $attribute)
    {
        if ($attribute->product_id != $product->id) {
            abort(404);
        }

        return view('admin.product-attribute.show', compact('product', 'attribute'));
    }

    /**
     * Show the form for editing the specified attribute.
     */
    public function edit(Product $product, ProductAttribute $attribute)
    {
        if ($attribute->product_id != $product->id) {
            abort(404);
        }

        return view('admin.product-attribute.edit', compact('product', 'attribute'));
    }

    /**
     * Update the specified attribute.
     */
    public function update(Request $request, Product $product, ProductAttribute $attribute)
    {
        if ($attribute->product_id != $product->id) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'attribute_name' => 'required|string|max:100',
            'attribute_value' => 'required|string',
            'attribute_group' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'attribute_name.required' => 'Attribute name is required.',
            'attribute_name.max' => 'Attribute name must not exceed 100 characters.',
            'attribute_value.required' => 'Attribute value is required.',
            'attribute_group.max' => 'Attribute group must not exceed 50 characters.',
            'sort_order.integer' => 'Sort order must be a number.',
            'sort_order.min' => 'Sort order must be at least 0.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $attribute->attribute_name = $request->attribute_name;
        $attribute->attribute_value = $request->attribute_value;
        $attribute->attribute_group = $request->attribute_group;
        $attribute->sort_order = $request->sort_order ?? 0;
        $attribute->save();

        return response()->json([
            'success' => true,
            'message' => 'Product attribute updated successfully.',
            'redirect' => route('products.attributes.index', $product->id)
        ]);
    }

    /**
     * Remove the specified attribute.
     */
    public function destroy(Product $product, ProductAttribute $attribute)
    {
        if ($attribute->product_id != $product->id) {
            abort(404);
        }

        $attribute->delete();
        // If the request expects JSON (AJAX), return JSON; otherwise redirect with flash message
        if (request()->expectsJson() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product attribute deleted successfully.'
            ]);
        }

        return redirect()
            ->route('products.attributes.index', $product->id)
            ->with('message', 'Product attribute deleted successfully.');
    }
}
