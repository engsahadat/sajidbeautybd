<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Exception;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $q = Supplier::with(['product','vendor']);
            if ($request->filled('product_id')) { $q->where('product_id', $request->integer('product_id')); }
            if ($request->filled('vendor_id')) { $q->where('vendor_id', $request->integer('vendor_id')); }
            if ($request->filled('is_primary')) { $q->where('is_primary', (bool)$request->input('is_primary')); }
            $suppliers = $q->orderByDesc('id')->paginate(20)->withQueryString();
            $products = Product::orderBy('name')->pluck('name','id');
            $vendors = Vendor::orderBy('id','desc')->pluck('name','id');
            return view('admin.suppliers.index', compact('suppliers','products','vendors'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load suppliers.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $products = Product::orderBy('name')->pluck('name','id');
            $vendors = Vendor::orderBy('id','desc')->pluck('name','id');
            return view('admin.suppliers.create', compact('products','vendors'));
        } catch (Exception $e) {
            return redirect()->route('suppliers.index')->with('error', 'Failed to load create form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request)
    {
        try {
            $supplier = Supplier::create($request->validated());
            return response()->json([
                'success'  => true,
                'message'  => 'Supplier created successfully.',
                'data'     => $supplier,
                'redirect' => route('suppliers.index')
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create supplier.',
                'errors'  => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        try {
            return view('admin.suppliers.show', compact('supplier'));
        } catch (Exception $e) {
            return redirect()->route('suppliers.index')->with('error', 'Supplier not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $products = Product::orderBy('name')->pluck('name','id');
            $vendors = Vendor::orderBy('id','desc')->pluck('id','id');
            return view('admin.suppliers.edit', compact('supplier','products','vendors'));
        } catch (Exception $e) {
            return redirect()->route('suppliers.index')->with('error', 'Supplier not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $supplier = Supplier::findOrFail($id);
            $supplier->update($data);
            return response()->json([
                'success'  => true,
                'message'  => 'Supplier updated successfully.',
                'data'     => $supplier,
                'redirect' => route('suppliers.index')
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update supplier.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            return redirect()->back()->with('message', 'Supplier deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete supplier.');
        }
    }
}
