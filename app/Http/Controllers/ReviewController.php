<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddReviewRequest;
use App\Models\Review;
use App\Models\Laundry; // Ubah dari Order ke Laundry
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
            'laundry_id' => $request->laundry_id, // Ubah dari order_id ke laundry_id
        ]);

        return response()->json([
            'message' => 'Review added successfully',
            'review' => $review,
        ], 201);
    }

    public function getAverageRating($laundryId) // Ubah dari orderId ke laundryId
    {
        $averageRating = Review::where('laundry_id', $laundryId)->avg('rating');

        return response()->json([
            'average_rating' => number_format($averageRating, 1),
        ]);
    }

    public function getReviews($laundryId) // Ubah dari orderId ke laundryId
    {
        $reviews = Review::where('laundry_id', $laundryId)->get();

        return response()->json([
            'reviews' => $reviews,
        ]);
    }
}
