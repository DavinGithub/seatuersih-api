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
        $laundries = Laundry::all();
        return response()->json([
            'message' => 'Laundries fetched successfully',
            'laundries' => $laundries,
        ], 200);
    }

    public function getLaundry($id)
    {
        $laundry = Laundry::find($id);
        if (!$laundry) {
            return response()->json([
                'message' => 'Laundry not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Laundry fetched successfully',
            'laundry' => $laundry,
        ], 200);
    }

    public function updateLaundry(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255', // Mengganti order_type dengan name
            'description' => 'nullable|string|max:255',
        ]);

        $laundry = Laundry::find($id);
        if (!$laundry) {
            return response()->json([
                'message' => 'Laundry not found',
            ], 404);
        }

        $laundry->update($request->only(['name', 'description'])); // Mengganti order_type dengan name

        return response()->json([
            'message' => 'Laundry updated successfully',
            'laundry' => $laundry,
        ], 200);
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
