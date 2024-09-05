<?php

namespace App\Http\Controllers;

use App\Models\Laundry;
use Illuminate\Http\Request;

class LaundryController extends Controller
{
    public function addLaundry(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255', // Mengganti order_type dengan name
            'description' => 'nullable|string|max:255',
        ]);
    
        $laundry = Laundry::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
    
        return response()->json([
            'message' => 'Laundry created successfully',
            'laundry' => $laundry,
        ], 201);
    }

    public function getLaundries()
    {
        $laundries = Laundry::with('reviews')->get();
        
            $laundries->each(function ($laundry) {
                $averageRating = $laundry->reviews()->avg('rating');
                $laundry->makeHidden('reviews');
                $laundry->average_rating = number_format($averageRating, 1);
        });

        return response()->json([
            'message' => 'Laundries fetched successfully',
            'laundries' => $laundries,
        ], 200);
    }

    public function getLaundry($id)
    {
        $laundry = Laundry::with('reviews')->find($id);
        if (!$laundry) {
            return response()->json([
                'message' => 'Laundry not found',
            ], 404);
        }

        $averageRating = $laundry->reviews()->avg('rating');
        $laundry->average_rating = number_format($averageRating, 1);

        unset($laundry->reviews);

        return response()->json([
            'message' => 'Laundry fetched successfully',
            'laundry' => $laundry,
        ], 200);
    }


    public function updateLaundry(Request $request, $id)
{
    $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'description' => 'nullable|string|max:255',
        'price' => 'nullable|string|max:255', // Menambahkan validasi untuk price
    ]);

    $laundry = Laundry::find($id);
    if (!$laundry) {
        return response()->json([
            'message' => 'Laundry not found',
        ], 404);
    }

    $laundry->update($request->only(['name', 'description', 'price'])); // Mengupdate kolom termasuk price

    return response()->json([
        'message' => 'Laundry updated successfully',
        'data' => $laundry,
    ]);
}


    public function deleteLaundry($id)
    {
        $laundry = Laundry::find($id);
        if (!$laundry) {
            return response()->json([
                'message' => 'Laundry not found',
            ], 404);
        }

        $laundry->delete();

        return response()->json([
            'message' => 'Laundry deleted successfully',
        ], 200);
    }
}
