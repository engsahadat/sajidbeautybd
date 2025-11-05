<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HomePageRequest;
use App\Models\HomePage;
use Exception;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = HomePage::query();
            if ($request->has('search') && !empty($request->input('search'))) {
                $q = $request->input('search');
                $query->where('title', 'LIKE', '%'.$q.'%')
                      ->orWhere('subtitle', 'LIKE', '%'.$q.'%');
            }
            $homePages = $query->paginate(10);
            return view('admin.home_pages.index', compact('homePages'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load records.');
        }
    }

    public function create()
    {
        try {
            return view('admin.home_pages.create');
        } catch (Exception $e) {
            return redirect()->route('home-pages.index')->with('error', 'Failed to load create form.');
        }
    }

    public function store(HomePageRequest $request)
    {
        try {
            $data = $request->validated();
            $saved = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $uniqueFileName  = time().'_'.uniqid().'_'.$file->getClientOriginalName();
                    $destinationPath = public_path('images/home');
                    if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);
                    $file->move($destinationPath, $uniqueFileName);
                    $saved[] = 'images/home/'.$uniqueFileName;
                }
            }
            if (!empty($saved)) {
                $data['images'] = $saved;
            }
            $homePage = HomePage::create($data);
            return response()->json([
                'success'  => true,
                'message'  => 'Home page item created successfully.',
                'data'     => $homePage,
                'redirect' => route('home-pages.index')
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create record.',
                'errors'  => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $homePage = HomePage::findOrFail($id);
            return view('admin.home_pages.show', compact('homePage'));
        } catch (Exception $e) {
            return redirect()->route('home-pages.index')->with('error', 'Record not found.');
        }
    }

    public function edit(string $id)
    {
        try {
            $homePage = HomePage::findOrFail($id);
            return view('admin.home_pages.edit', compact('homePage'));
        } catch (Exception $e) {
            return redirect()->route('home-pages.index')->with('error', 'Record not found.');
        }
    }

    public function update(HomePageRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $homePage = HomePage::findOrFail($id);
            
            $existing = is_array($homePage->images) ? $homePage->images : ($homePage->image ? [$homePage->image] : []);
            
            // Upload new images
            $uploaded = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $uniqueFileName  = time().'_'.uniqid().'_'.$file->getClientOriginalName();
                    $destinationPath = public_path('images/home');
                    if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);
                    $file->move($destinationPath, $uniqueFileName);
                    $uploaded[] = 'images/home/'.$uniqueFileName;
                }
            }
            
            // Handle image management based on options
            if ($request->input('remove_all_images') == '1') {
                // Remove all existing images
                foreach ($existing as $p) {
                    if ($p && file_exists(public_path($p))) @unlink(public_path($p));
                }
                $data['images'] = $uploaded; // Only keep new uploads
            } elseif (!empty($uploaded)) {
                // If new images uploaded, replace old ones by default
                if ($request->input('keep_existing_images') != '1') {
                    // Delete old images and replace with new ones
                    foreach ($existing as $p) {
                        if ($p && file_exists(public_path($p))) @unlink(public_path($p));
                    }
                    $data['images'] = $uploaded;
                } else {
                    // Keep existing images and append new ones
                    $merged = array_values(array_filter(array_merge($existing, $uploaded)));
                    $data['images'] = $merged;
                }
            } else {
                // No new images uploaded, keep existing
                $data['images'] = $existing;
            }
            
            $homePage->update($data);
            return response()->json([
                'success'  => true,
                'message'  => 'Home page item updated successfully.',
                'data'     => $homePage,
                'redirect' => route('home-pages.index')
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update record.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $homePage = HomePage::findOrFail($id);
            $all = is_array($homePage->images) ? $homePage->images : [];
            foreach ($all as $p) {
                if ($p && file_exists(public_path($p))) @unlink(public_path($p));
            }
            $homePage->delete();
            return redirect()->back()->with('message', 'Record deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete record.');
        }
    }
}
