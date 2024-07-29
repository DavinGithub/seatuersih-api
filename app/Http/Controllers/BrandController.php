<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddBrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function addBrand(AddBrandRequest $request)
    {
        $request->validated();

        $brand = Brand::create([
            'name' => $request->name,
            'order_id' => $request->order_id,
        ]);

        return response()->json([
            'message' => 'Brand added successfully',
            'brand' => $brand,
        ], 201);
    }

    public function updateBrand(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:brands,id',
            'name' => 'sometimes|required|string|max:255',
            'order_id' => 'sometimes|required|integer|exists:orders,id',
        ]);

        $brand = Brand::find($request['id']);
        if (!$brand) {
            return response()->json([
                'message' => 'Brand not found',
            ], 404);
        }

        $brand->update($request->all());

        return response()->json([
            'message' => 'Brand updated successfully',
            'brand' => $brand,
        ], 200);
    }

    public function deleteBrand($id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json([
                'message' => 'Brand not found',
            ], 404);
        }

        $brand->delete();

        return response()->json([
            'message' => 'Brand deleted successfully',
        ], 200);
    }

    public function getBrands(Request $request)
{
    $order_id = $request->input('order_id');

    // Jika ada order_id, filter berdasarkan order_id, jika tidak, ambil semua brand
    if ($order_id) {
        $brands = Brand::where('order_id', $order_id)->get();
    } else {
        $brands = Brand::all();
    }

    if ($brands->isEmpty()) {
        return response()->json([
            'message' => 'No brands found',
        ], 200);
    }

    return response()->json([
        'message' => 'Brands list',
        'data' => $brands,
    ], 200);
}


    public function getBrand($id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json([
                'message' => 'Brand not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Brand details',
            'data' => $brand,
        ], 200);
    }

    public function getBrandsByUserId($userId)
{
    $brands = Brand::where('order_id', $userId)->get();

    if ($brands->isEmpty()) {
        return response()->json([
            'message' => 'No brands found for this user',
        ], 200);
    }

    return response()->json([
        'message' => 'Brands list for the user',
        'data' => $brands,
    ], 200);
}
public function getBrandsByOrderId($order_id)
{
    $brands = Brand::where('order_id', $order_id)->get();

    if ($brands->isEmpty()) {
        return response()->json([
            'message' => 'No brands found for this order',
        ], 200);
    }

    return response()->json([
        'message' => 'Brands list for the order',
        'data' => $brands,
    ], 200);
}


}
