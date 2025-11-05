<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountRequest;
use App\Models\Discount;
use Exception;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Discount::query();
            if ($request->filled('search')) {
                $term = $request->input('search');
                $query->where('title', 'LIKE', "%{$term}%");
            }
            $discounts = $query->orderByDesc('id')->paginate(15);
            return view('admin.discount.index', compact('discounts'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load discounts.');
        }
    }

    public function create()
    {
        try {
            return view('admin.discount.create');
        } catch (Exception $e) {
            return redirect()->route('discounts.index')->with('error', 'Failed to load create form.');
        }
    }

    public function store(DiscountRequest $request)
    {
        try {
            $data = $request->validated();
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $uniqueFileName = time().'_'.$image->getClientOriginalName();
                $destinationPath = public_path('images/discounts');
                if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);
                $image->move($destinationPath, $uniqueFileName);
                $data['image'] = 'images/discounts/'.$uniqueFileName;
            }
            $discount = Discount::create($data);
            return response()->json([
                'success' => true,
                'message' => 'Discount created successfully.',
                'data' => $discount,
                'redirect' => route('discounts.index')
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create discount.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $discount = Discount::findOrFail($id);
            return view('admin.discount.edit', compact('discount'));
        } catch (Exception $e) {
            return redirect()->route('discounts.index')->with('error', 'Discount not found.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $discount = Discount::findOrFail($id);
            return view('admin.discount.show', compact('discount'));
        } catch (Exception $e) {
            return redirect()->route('discounts.index')->with('error', 'Discount not found.');
        }
    }

    public function update(DiscountRequest $request, $id)
    {
        try {
            $discount = Discount::findOrFail($id);
            $data = $request->validated();
            if ($request->input('remove_image') == '1' && $discount->image) {
                if (file_exists(public_path($discount->image))) unlink(public_path($discount->image));
                $data['image'] = null;
            }
            if ($request->hasFile('image')) {
                if ($discount->image && file_exists(public_path($discount->image))) unlink(public_path($discount->image));
                $image = $request->file('image');
                $uniqueFileName = time().'_'.$image->getClientOriginalName();
                $destinationPath = public_path('images/discounts');
                if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);
                $image->move($destinationPath, $uniqueFileName);
                $data['image'] = 'images/discounts/'.$uniqueFileName;
            }
            $discount->update($data);
            return response()->json([
                'success' => true,
                'message' => 'Discount updated successfully.',
                'data' => $discount,
                'redirect' => route('discounts.index')
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update discount.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $discount = Discount::findOrFail($id);
            if ($discount->image && file_exists(public_path($discount->image))) unlink(public_path($discount->image));
            $discount->delete();
            return redirect()->back()->with('message', 'Discount deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete discount.');
        }
    }
}
