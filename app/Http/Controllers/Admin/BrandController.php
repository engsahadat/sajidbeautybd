<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Brand::query();
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%");
                });
            }
            $brands = $query->paginate(20);
            return view('admin.brand.index', compact('brands'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load brands');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request)
    {
        try {
            $data = $request->validated();
            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $uniqueFileName = time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('images/brand');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $image->move($destinationPath, $uniqueFileName);
                $data['logo'] = 'images/brand/' . $uniqueFileName;
            }
            $brand = Brand::create($data);
            return response()->json([
                'success' => true,
                'message' => 'Brand created successfully.',
                'data' => $brand,
                'redirect' => route('brands.index')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create brand.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brand.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $brand = Brand::findOrFail($id);

            if ($request->input('remove_logo') == '1' && $brand->logo) {
                if (file_exists(public_path($brand->logo))) {
                    @unlink(public_path($brand->logo));
                }
                $data['logo'] = null;
            }

            if ($request->hasFile('logo')) {
                if ($brand->logo && file_exists(public_path($brand->logo))) {
                    @unlink(public_path($brand->logo));
                }
                $image = $request->file('logo');
                $uniqueFileName = time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('images/brand');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $image->move($destinationPath, $uniqueFileName);
                $data['logo'] = 'images/brand/' . $uniqueFileName;
            }

            $brand->update($data);
            return response()->json([
                'success' => true,
                'message' => 'Brand updated successfully.',
                'data' => $brand,
                'redirect' => route('brands.index')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update brand.',
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
            $brand = Brand::findOrFail($id);
            if ($brand->logo && file_exists(public_path($brand->logo))) {
                @unlink(public_path($brand->logo));
            }
            $brand->delete();
            return back()->with('message', 'Brand deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete brand.');
        }
    }
}
