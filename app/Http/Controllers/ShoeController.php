<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddShoeRequest;
use App\Models\Shoe;
use App\Models\Order; // Import the Order model
use Illuminate\Http\Request;

class ShoeController extends Controller
{
    public function addShoe(AddShoeRequest $request)
    {
        $request->validated();


        $shoe = Shoe::create([
            'name' => $request->name,
            'addons' => $request->addons,
            'notes' =>  $request-> notes,
            'price' => $request -> price,
            'order_id' => $request->order_id,
            
        ]);

        return response()->json([
            'message' => 'Shoe added successfully',
            'shoe' => $shoe,
        ], 201);
    }

    public function updateShoe(Request $request)
    {
         $request->validate([
            'id' => 'required|integer|exists:shoes,id',
            'name' => 'sometimes|required|string|max:255',
            'addons' => 'sometimes|nullable|string|max:255',
            'notes' => 'sometimes|nullable|string|max:255',
            'price' => 'sometimes|required|numeric',
            'order_id' => 'sometimes|required|integer|exists:orders,id',
        ]);

        $shoe = Shoe::find($request['id']);
        if (!$shoe) {
            return response()->json([
                'message' => 'Shoe not found',
            ], 404);
        }

        $shoe->update($request);

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
