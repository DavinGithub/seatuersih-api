<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddReviewRequest;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function addReview(AddReviewRequest $request)
    {
        $user = auth()->user();
        $review = Review::create([ 
            'review' => $request->input('review'),
            'rating' => $request->input('rating'),
            'user_id' => $user->id,
            'order_id' => $request->order_id,
        ]);

        return response()->json([
            'message' => 'Review added successfully',
            'review' => $review,
        ], 201);
    }
}
