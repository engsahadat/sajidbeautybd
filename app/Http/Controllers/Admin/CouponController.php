<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use Exception;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $q = Coupon::query();
            if ($search = $request->input('q')) {
                $q->where(function($s) use ($search){
                    $s->where('code','like',"%$search%")
                      ->orWhere('name','like',"%$search%");
                });
            }
            if ($status = $request->input('status')) {
                $q->where('status', $status);
            }
            $coupons = $q->orderByDesc('id')->paginate(15)->withQueryString();
            return view('admin.coupons.index', compact('coupons'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load coupons.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('admin.coupons.create');
        } catch (Exception $e) {
            return redirect()->route('coupons.index')->with('error', 'Failed to load create form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CouponRequest $request)
    {
        try {
            $data = $request->validated();
            $coupon = Coupon::create($data);
            return response()->json([
                'success'  => true,
                'message'  => 'Coupon created successfully.',
                'data'     => $coupon,
                'redirect' => route('coupons.index')
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create coupon.',
                'errors'  => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            return view('admin.coupons.edit', compact('coupon'));
        } catch (Exception $e) {
            return redirect()->route('coupons.index')->with('error', 'Coupon not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CouponRequest $request, string $id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            $data = $request->validated();
            $coupon->update($data);
            return response()->json([
                'success'  => true,
                'message'  => 'Coupon updated successfully.',
                'data'     => $coupon,
                'redirect' => route('coupons.index')
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update coupon.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        try {
            $coupon->delete();
            return redirect()->back()->with('message', 'Coupon deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete coupon.');
        }
    }
}
