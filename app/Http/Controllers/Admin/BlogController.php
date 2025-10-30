<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Blog::with('author');
            if ($request->has('search') && !empty($request->input('search'))) {
                $searchTerm = $request->input('search');
                $query->where('title', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('slug', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
            }
            if ($request->has('status') && !empty($request->input('status'))) {
                $query->where('status', $request->input('status'));
            }
            $blogs = $query->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(10);
            return view('admin.blog.index', compact('blogs'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load blogs.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $authors = User::orderBy('first_name')->pluck('first_name', 'id');
            return view('admin.blog.create', compact('authors'));
        } catch (Exception $e) {
            return redirect()->route('blogs.index')->with('error', 'Failed to load create form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRequest $request)
    {
        try {
            $data = $request->validated();
            if ($request->hasFile('image')) {
                $image           = $request->file('image');
                $uniqueFileName  = time().'_'.$image->getClientOriginalName();
                $destinationPath = public_path('images/blog');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                
                $image->move($destinationPath, $uniqueFileName);
                $filePath = 'images/blog/'.$uniqueFileName;
            }
            $data['image'] = $filePath ?? null;
            $data['published_at'] = $data['published_at'] ?? now();
            $blog = Blog::create($data);
            return response()->json([
                'success'  => true,
                'message'  => 'Blog created successfully.',
                'data'     => $blog,
                'redirect' => route('blogs.index')
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create blog.',
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
            $blog = Blog::with('author')->findOrFail($id);
            return view('admin.blog.show', compact('blog'));
        } catch (Exception $e) {
            return redirect()->route('blogs.index')->with('error', 'Blog not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $blog = Blog::findOrFail($id);
            $authors = User::orderBy('first_name')->pluck('first_name', 'id');
            return view('admin.blog.edit', compact('blog', 'authors'));
        } catch (Exception $e) {
            return redirect()->route('blogs.index')->with('error', 'Blog not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $blog = Blog::findOrFail($id);
            if ($request->input('remove_image') == '1' && $blog->image) {
                if (file_exists(public_path($blog->image))) {
                    unlink(public_path($blog->image));
                }
                $data['image'] = null;
            }
            if ($request->hasFile('image')) {
                if ($blog->image && file_exists(public_path($blog->image))) {
                    unlink(public_path($blog->image));
                }
                $image = $request->file('image');
                $uniqueFileName  = time().'_'.$image->getClientOriginalName();
                $destinationPath = public_path('images/blog');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $image->move($destinationPath, $uniqueFileName);
                $data['image'] = 'images/blog/'.$uniqueFileName;
            }
            $blog->update($data);
                return response()->json([
                    'success'  => true,
                    'message'  => 'Blog updated successfully.',
                    'data'     => $blog,
                    'redirect' => route('blogs.index')
                ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update blog.',
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
            $blog = Blog::findOrFail($id);
            if ($blog->image && file_exists(public_path($blog->image))) {
                unlink(public_path($blog->image));
            }
            $blog->delete();
            return redirect()->back()->with('message', 'Blog deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete blog.');
        }
    }
}
