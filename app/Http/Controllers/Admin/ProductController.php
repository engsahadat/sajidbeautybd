<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Product::with(['category', 'brand']);
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            }
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->input('category_id'));
            }
            if ($request->filled('brand_id')) {
                $query->where('brand_id', $request->input('brand_id'));
            }

            $products = $query->orderByDesc('id')->paginate(10);
            $categories = Category::orderBy('name')->get(['id','name']);
            $brands = Brand::orderBy('name')->get(['id','name']);
            return view('admin.Product.index', compact('products', 'categories', 'brands'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load products.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $categories = Category::orderBy('name')->get(['id','name']);
            $brands = Brand::orderBy('name')->get(['id','name']);
            return view('admin.Product.create', compact('categories', 'brands'));
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Failed to load create form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try {
            $data = $request->validated();
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $uniqueFileName = time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('images/product');
                if (!file_exists($destinationPath)) { mkdir($destinationPath, 0755, true); }
                $image->move($destinationPath, $uniqueFileName);
                // Store main image path as string in `image`
                $data['image'] = 'images/product/' . $uniqueFileName;
            }
            if ($request->hasFile('gallery')) {
                // handleGalleryUpload returns an array of paths; since gallery is cast to array, assign directly
                $data['gallery'] = $this->handleGalleryUpload($request->file('gallery'));
            }
            $product = Product::create($data);
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully.',
                'data' => $product->load('category','brand'),
                'redirect' => route('products.index')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::with(['category','brand'])->findOrFail($id);
            return view('admin.Product.show', compact('product'));
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Product not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $categories = Category::orderBy('name')->get(['id','name']);
            $brands = Brand::orderBy('name')->get(['id','name']);
            return view('admin.Product.edit', compact('product','categories','brands'));
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Product not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $product = Product::findOrFail($id);
            // Remove current image if requested
            if ($request->boolean('remove_image') && $product->image) {
                $path = public_path($product->image);
                if (is_file($path)) @unlink($path);
                $product->image = null;
                $product->save();
            }
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $uniqueFileName = time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('images/product');
                if (!file_exists($destinationPath)) { mkdir($destinationPath, 0755, true); }
                $image->move($destinationPath, $uniqueFileName);
                // Replace main image string in `image`
                $data['image'] = 'images/product/' . $uniqueFileName;
            }
            // Manage existing gallery keep/delete
            $currentGallery = [];
            if (is_array($product->gallery)) {
                $currentGallery = $product->gallery;
            } elseif (!empty($product->getRawOriginal('gallery')) && is_string($product->getRawOriginal('gallery'))) {
                $decoded = json_decode($product->getRawOriginal('gallery'), true);
                $currentGallery = is_array($decoded) ? $decoded : [];
            }
            $keep = $request->input('keep_gallery', []);
            $delete = $request->input('delete_gallery', []);
            if (!empty($delete)) {
                foreach ($delete as $del) {
                    $p = public_path($del);
                    if (is_file($p)) @unlink($p);
                }
            }
            // Keep only those paths in keep[]
            $newGallery = array_values(array_filter($currentGallery, function($g) use ($keep) { return in_array($g, $keep); }));

            // Append new uploads
            if ($request->hasFile('gallery')) {
                $uploaded = $this->handleGalleryUpload($request->file('gallery'));
                $newGallery = array_values(array_merge($newGallery, $uploaded));
            }
            $data['gallery'] = $newGallery;
            $product->update($data);
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully.',
                'data' => $product->load('category','brand'),
                'redirect' => route('products.index')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return redirect()->route('products.index')->with('message', 'Product deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Failed to delete product.');
        }
    }

    protected function handleGalleryUpload($files, $oldGallery = null)
    {
        $galleryFiles = [];
        // If oldGallery is already an array (casted), use as-is; if string JSON, decode; else empty array
        if (is_array($oldGallery)) {
            $old = $oldGallery;
        } elseif (is_string($oldGallery)) {
            $old = json_decode($oldGallery, true) ?: [];
        } else {
            $old = [];
        }
        foreach ($files as $file) {
            $unique = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('images/product');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $file->move($destPath, $unique);
            $galleryFiles[] = 'images/product/' . $unique;
        }
        return array_values(array_merge($old, $galleryFiles));
    }

    /**
     * List product reviews (all or for a specific product), with filters and pagination.
     * Routes:
     *  - GET /admin/products/reviews (name: products.reviews.index)
     *  - GET /admin/products/{product}/reviews (name: products.reviews.view)
     */
    public function reviews(Request $request, ?Product $product = null)
    {
        try {
            $query = ProductReview::with(['product']);

            // Scope by product if provided in the URL
            $selectedProductId = null;
            if ($product && $product->id) {
                $selectedProductId = (int) $product->id;
                $query->where('product_id', $selectedProductId);
            } elseif ($request->filled('product_id')) {
                $selectedProductId = (int) $request->integer('product_id');
                if ($selectedProductId) {
                    $query->where('product_id', $selectedProductId);
                }
            }

            // Search by title or body
            if ($request->filled('search')) {
                $search = trim($request->input('search'));
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('review', 'like', "%{$search}%");
                });
            }

            // Rating filter
            if ($request->filled('rating')) {
                $rating = (int) $request->input('rating');
                if ($rating >= 1 && $rating <= 5) {
                    $query->where('rating', $rating);
                }
            }

            // Verified purchase filter
            if ($request->filled('verified')) {
                $verified = $request->input('verified');
                if ($verified === '1' || $verified === '0') {
                    $query->where('is_verified_purchase', $verified === '1');
                }
            }

            // Status filter (optional values depend on your data; we support any exact match)
            if ($request->filled('status')) {
                $query->where('status', $request->input('status'));
            }

            $reviews = $query->orderByDesc('id')->paginate(12)->appends($request->query());

            // Products for dropdown filter
            $products = Product::orderBy('name')->pluck('name', 'id');

            return view('admin.Product.reviews', [
                'reviews' => $reviews,
                'products' => $products,
                'selectedProductId' => $selectedProductId,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load product reviews.');
        }
    }

    /**
     * Delete a specific review for a product.
     * Route: DELETE /admin/products/{product}/reviews/{review}
     */
    public function deleteReview(Product $product, ProductReview $review)
    {
        try {
            if ($review->product_id !== $product->id) {
                return redirect()->back()->with('error', 'Review does not belong to the selected product.');
            }
            $review->delete();
            return redirect()->back()->with('message', 'Review deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete review.');
        }
    }
}
