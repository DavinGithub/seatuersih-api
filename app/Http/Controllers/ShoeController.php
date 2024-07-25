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

    public function getShoes(Request $request)
{
    // Ambil order_id jika ada dalam request, jika tidak, default ke null
    $order_id = $request->input('order_id', null);

    // Jika order_id diberikan, filter berdasarkan order_id, jika tidak, ambil semua data
    if ($order_id) {
        $shoes = Shoe::where('order_id', $order_id)->get();
    } else {
        $shoes = Shoe::all();
    }

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
