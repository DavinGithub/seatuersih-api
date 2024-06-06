<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddShoeRequest;
use App\Models\Shoe;
use Illuminate\Http\Request;

class ShoeController extends Controller
{
    public function addShoe(AddShoeRequest $request)
    {
        
        $validated = $request->validated();

        $shoe = Shoe::create([
            'name' => $validated['name'],
            'addons' => $validated['addons'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'price' => $validated['price'],
        ]);

        return response()->json([
            'message' => 'Shoe added successfully',
            'shoe' => $shoe,
        ], 201);
    }

    public function updateShoe(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:shoes,id',
            'name' => 'sometimes|required|string|max:255',
            'addons' => 'sometimes|nullable|string|max:255',
            'notes' => 'sometimes|nullable|string|max:255',
            'price' => 'sometimes|required|numeric',
        ]);

        $shoe = Shoe::find($validated['id']);
        if (!$shoe) {
            return response()->json([
                'message' => 'Shoe not found',
            ], 404);
        }

        $shoe->update($validated);

        return response()->json([
            'message' => 'Shoe updated successfully',
            'shoe' => $shoe,
        ], 200);
    }

    public function deleteShoe($id)
    {
        $shoe = Shoe::find($id);
        if (!$shoe) {
            return response()->json([
                'message' => 'Shoe not found',
            ], 404);
        }

        $shoe->delete();

        return response()->json([
            'message' => 'Shoe deleted successfully',
        ], 200);
    }

    public function getShoes()
    {
        $shoes = Shoe::all();
        if ($shoes->isEmpty()) {
            return response()->json([
                'message' => 'No shoes found',
            ], 200);
        }

        return response()->json([
            'message' => 'Shoes list',
            'data' => $shoes,
        ], 200);
    }

    public function getShoe($id)
    {
        $shoe = Shoe::find($id);
        if (!$shoe) {
            return response()->json([
                'message' => 'Shoe not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Shoe details',
            'data' => $shoe,
        ], 200);
    }
}
