<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Category::query();
            if ($request->has('search') && !empty($request->input('search'))) {
                $searchTerm = $request->input('search');
                $query->where('name', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('slug', 'LIKE', '%' . $searchTerm . '%');
            }
            $categories = $query->paginate(20);
            return view('admin.category.index', compact('categories'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load categories.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('admin.category.create');
        } catch (Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Failed to load create form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        try {
            $data = $request->validated();
            if ($request->hasFile('image')) {
                $image           = $request->file('image');
                $uniqueFileName  = time().'_'.$image->getClientOriginalName();
                $destinationPath = public_path('images/category');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                
                $image->move($destinationPath, $uniqueFileName);
                $filePath = 'images/category/'.$uniqueFileName;
            }
            $data['image'] = $filePath ?? null;
            $category = Category::create($data);
            return response()->json([
                'success'  => true,
                'message'  => 'Category created successfully.',
                'data'     => $category,
                'redirect' => route('categories.index')
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category.',
                'errors'  => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            return view('admin.category.show', compact('category'));
        } catch (Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Category not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            return view('admin.category.edit', compact('category'));
        } catch (Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Category not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $category = Category::findOrFail($id);
            if ($request->input('remove_image') == '1' && $category->image) {
                if (file_exists(public_path($category->image))) {
                    unlink(public_path($category->image));
                }
                $data['image'] = null;
            }
            if ($request->hasFile('image')) {
                if ($category->image && file_exists(public_path($category->image))) {
                    unlink(public_path($category->image));
                }
                $image = $request->file('image');
                $uniqueFileName  = time().'_'.$image->getClientOriginalName();
                $destinationPath = public_path('images/category');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $image->move($destinationPath, $uniqueFileName);
                $data['image'] = 'images/category/'.$uniqueFileName;
            }
            $category->update($data);
                return response()->json([
                    'success'  => true,
                    'message'  => 'Category updated successfully.',
                    'data'     => $category,
                    'redirect' => route('categories.index')
                ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            if ($category->image && file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }
            $category->delete();
            return redirect()->back()->with('message', 'Category deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete category.');
        }
    }
}
