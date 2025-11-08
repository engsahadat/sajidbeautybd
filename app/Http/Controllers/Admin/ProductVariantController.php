<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource for a product.
     */
    public function index(Request $request, Product $product)
    {
        try {
            $query = ProductVariant::where('product_id', $product->id)
                ->select(['id', 'name', 'value', 'sku', 'price', 'stock_quantity', 'is_default', 'image', 'sort_order', 'created_at']);
            
            // Apply search filter
            if ($request->has('search') && !empty($request->input('search'))) {
                $searchTerm = $request->input('search');
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('value', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('sku', 'LIKE', '%' . $searchTerm . '%');
                });
            }
            
            $variants = $query->orderBy('sort_order')->orderByDesc('id')->paginate(20)->withQueryString();
            
            return view('admin.product-variant.index', compact('variants', 'product'));
        } catch (Exception $e) {
            Log::error('Variant index error: ' . $e->getMessage());
            return redirect()->route('products.index')->with('error', 'Failed to load variants.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Product $product)
    {
        try {
            return view('admin.product-variant.create', compact('product'));
        } catch (Exception $e) {
            return redirect()->route('products.variants.index', $product)->with('error', 'Failed to load create form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'value' => 'required|string|max:100',
                'sku' => 'nullable|string|max:50|unique:product_variants,sku',
                'price' => 'nullable|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'is_default' => 'boolean',
                'image' => 'nullable|image|max:2048',
                'sort_order' => 'nullable|integer',
                'display_style' => 'nullable|string|in:rectangle,circle,image,color,radio,dropdown',
                'color_code' => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
                'swatch_image' => 'nullable|image|max:1024',
            ]);

            $validated['product_id'] = $product->id;

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $uniqueFileName = time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('images/variant');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $image->move($destinationPath, $uniqueFileName);
                $validated['image'] = 'images/variant/' . $uniqueFileName;
            }

            // Handle swatch image upload
            if ($request->hasFile('swatch_image')) {
                $swatchImage = $request->file('swatch_image');
                $swatchFileName = 'swatch_' . time() . '_' . $swatchImage->getClientOriginalName();
                $destinationPath = public_path('images/variant/swatches');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $swatchImage->move($destinationPath, $swatchFileName);
                $validated['swatch_image'] = 'images/variant/swatches/' . $swatchFileName;
            }

            // If this is set as default, unset other defaults
            if (!empty($validated['is_default'])) {
                ProductVariant::where('product_id', $product->id)
                    ->update(['is_default' => false]);
            }

            $variant = ProductVariant::create($validated);
            return response()->json([
                'success' => true,
                'message' => 'Variant created successfully.',
                'data' => $variant,
                'redirect' => route('products.variants.index', $product)
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create variant.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product, ProductVariant $variant)
    {
        try {
            if ($variant->product_id !== $product->id) {
                return redirect()->route('products.variants.index', $product)->with('error', 'Variant not found.');
            }
            return view('admin.product-variant.show', compact('variant', 'product'));
        } catch (Exception $e) {
            return redirect()->route('products.variants.index', $product)->with('error', 'Variant not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product, ProductVariant $variant)
    {
        try {
            if ($variant->product_id !== $product->id) {
                return redirect()->route('products.variants.index', $product)->with('error', 'Variant not found.');
            }
            return view('admin.product-variant.edit', compact('variant', 'product'));
        } catch (Exception $e) {
            return redirect()->route('products.variants.index', $product)->with('error', 'Variant not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        try {
            if ($variant->product_id !== $product->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Variant does not belong to this product.'
                ], 400);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'value' => 'required|string|max:100',
                'sku' => 'nullable|string|max:50|unique:product_variants,sku,' . $variant->id,
                'price' => 'nullable|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'is_default' => 'boolean',
                'image' => 'nullable|image|max:2048',
                'sort_order' => 'nullable|integer',
                'remove_image' => 'boolean',
                'display_style' => 'nullable|string|in:rectangle,circle,image,color,radio,dropdown',
                'color_code' => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
                'swatch_image' => 'nullable|image|max:1024',
                'remove_swatch_image' => 'boolean',
            ]);

            // Handle image removal
            if ($request->input('remove_image') == '1' && $variant->image) {
                $path = public_path($variant->image);
                if (is_file($path)) {
                    @unlink($path);
                }
                $validated['image'] = null;
            }

            // Handle new image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($variant->image && file_exists(public_path($variant->image))) {
                    @unlink(public_path($variant->image));
                }
                
                $image = $request->file('image');
                $uniqueFileName = time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('images/variant');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $image->move($destinationPath, $uniqueFileName);
                $validated['image'] = 'images/variant/' . $uniqueFileName;
            }

            // Handle swatch image removal
            if ($request->input('remove_swatch_image') == '1' && $variant->swatch_image) {
                $path = public_path($variant->swatch_image);
                if (is_file($path)) {
                    @unlink($path);
                }
                $validated['swatch_image'] = null;
            }

            // Handle new swatch image upload
            if ($request->hasFile('swatch_image')) {
                // Delete old swatch image
                if ($variant->swatch_image && file_exists(public_path($variant->swatch_image))) {
                    @unlink(public_path($variant->swatch_image));
                }
                
                $swatchImage = $request->file('swatch_image');
                $swatchFileName = 'swatch_' . time() . '_' . $swatchImage->getClientOriginalName();
                $destinationPath = public_path('images/variant/swatches');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $swatchImage->move($destinationPath, $swatchFileName);
                $validated['swatch_image'] = 'images/variant/swatches/' . $swatchFileName;
            }

            // If this is set as default, unset other defaults
            if (!empty($validated['is_default']) && !$variant->is_default) {
                ProductVariant::where('product_id', $product->id)
                    ->where('id', '!=', $variant->id)
                    ->update(['is_default' => false]);
            }

            $variant->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Variant updated successfully.',
                'data' => $variant,
                'redirect' => route('products.variants.index', $product)
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update variant.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, ProductVariant $variant)
    {
        try {
            if ($variant->product_id !== $product->id) {
                return redirect()->back()->with('error', 'Variant does not belong to this product.');
            }

            // Delete variant image if exists
            if ($variant->image && file_exists(public_path($variant->image))) {
                @unlink(public_path($variant->image));
            }

            $variant->delete();

            return redirect()->route('products.variants.index', $product)->with('message', 'Variant deleted successfully.');
        } catch (Exception $e) {
            Log::error('Variant delete error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete variant.');
        }
    }
}
