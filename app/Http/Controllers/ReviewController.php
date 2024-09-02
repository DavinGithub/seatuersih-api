<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddReviewRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function addReview(AddReviewRequest $request)
    {
        $user = auth()->user();
        $review = Review::create([ 
            'order_type' => $request->order_type,
            'review' => $request->review,
            'rating' => $request->rating,
            'user_id' => $user->id,
            'laundry_id' => $request->laundry_id, 
            'order_id' => $request->order_id, 
            'review_date' => now()
        ]);

        $this->firebaseService->sendToAdmin(
            'Review Baru Ditambahkan',
            'Seseorang baru saja menambahkan review, dengan rating ' . $request->rating,
            '',
            ['route' => '/reviews/' . $review->id, 'data' => $review->id]
        );

        return response()->json([
            'message' => 'Review added successfully',
            'review' => $review,
        ], 201);    
    }

    public function getAverageRating($laundryId)
    {
        $averageRating = Review::where('laundry_id', $laundryId)->avg('rating');

        return response()->json([
            'average_rating' => number_format($averageRating, 1),
        ]);
    }   

    public function getReviews($laundryId)
    {
        $reviews = Review::where('laundry_id', $laundryId)->get();
        
        $reviews->load('user');

        return response()->json([
            'reviews' => $reviews,
        ]);
    }
}
