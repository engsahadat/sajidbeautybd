<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductReviewController extends Controller
{
    /**
     * Store a new product review for the authenticated user.
     */
    public function store(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'redirect' => route('login'),
                'message' => 'Please login to submit a review.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:100',
            'review' => 'required|string|min:10|max:2000',
        ], [
            'rating.required' => 'Please select a rating.',
            'rating.min' => 'Rating must be at least 1 star.',
            'rating.max' => 'Rating cannot exceed 5 stars.',
            'review.required' => 'Please write your review.',
            'review.min' => 'Review must be at least 10 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();

        // Check if the user already reviewed this product; allow update if exists
        $existing = ProductReview::where('product_id', $product->id)
            ->where('user_id', $user->id)
            ->first();

        // Determine verified purchase
        $verifiedOrderItem = OrderItem::where('product_id', $product->id)
            ->whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->whereIn('payment_status', ['paid', 'partially_refunded', 'refunded']);
            })
            ->latest('id')
            ->first();

        $data = [
            'product_id' => $product->id,
            'user_id' => $user->id,
            'order_id' => $verifiedOrderItem?->order_id,
            'rating' => (int) $request->integer('rating'),
            'title' => $request->input('title'),
            'review' => $request->input('review'),
            'is_verified_purchase' => (bool) $verifiedOrderItem,
            'status' => config('app.env') === 'production' ? 'pending' : 'approved', // auto-approve on non-prod
        ];

        if ($existing) {
            $existing->update($data);
            $review = $existing;
            $message = 'Your review has been updated.';
        } else {
            $review = ProductReview::create($data);
            $message = 'Thank you! Your review has been submitted.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'review' => $review,
        ]);
    }
}
