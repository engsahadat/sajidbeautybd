<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Models\Vendor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Vendor::query();
            if ($request->has('q') && !empty($request->input('q'))) {
                $searchTerm = $request->input('q');
                $query->where(function ($w) use ($searchTerm) {
                    $w->where('name', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('company', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('email', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('phone', 'LIKE', '%' . $searchTerm . '%');
                });
            }
            if ($request->has('status') && !empty($request->input('status'))) {
                $query->where('status', $request->input('status'));
            }
            $vendors = $query->orderBy('name')->paginate(10);
            return view('admin.vendors.index', compact('vendors'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load vendors.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('admin.vendors.create');
        } catch (Exception $e) {
            return redirect()->route('vendors.index')->with('error', 'Failed to load create form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VendorRequest $request)
    {
        try {
            $data = $request->validated();
            $vendor = Vendor::create($data);
            return response()->json([
                'success'  => true,
                'message'  => 'Vendor created successfully.',
                'data'     => $vendor,
                'redirect' => route('vendors.index')
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create vendor.',
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
            $vendor = Vendor::findOrFail($id);
            return view('admin.vendors.show', compact('vendor'));
        } catch (Exception $e) {
            return redirect()->route('vendors.index')->with('error', 'Vendor not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $vendor = Vendor::findOrFail($id);
            return view('admin.vendors.edit', compact('vendor'));
        } catch (Exception $e) {
            return redirect()->route('vendors.index')->with('error', 'Vendor not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VendorRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $vendor = Vendor::findOrFail($id);
            $vendor->update($data);
            return response()->json([
                'success'  => true,
                'message'  => 'Vendor updated successfully.',
                'data'     => $vendor,
                'redirect' => route('vendors.index')
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update vendor.',
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
            $vendor = Vendor::findOrFail($id);
            $vendor->delete();
            return redirect()->back()->with('message', 'Vendor deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete vendor.');
        }
    }
}
