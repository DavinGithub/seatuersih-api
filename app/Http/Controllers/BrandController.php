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
            'brand' => $request->brand,
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
            'brand' => 'sometimes|required|string|max:255',
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

    public function getBrands()
    {
        $brands = Brand::all();

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
        $brands = Brand::where('user_id', $userId)->get();

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
}
