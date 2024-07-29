<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use Illuminate\Http\Request;

class KabupatenController extends Controller
{
    public function addKabupaten(Request $request)
    {
        $request->validate([
            'kabupaten' => 'required|string|max:255',
            'laundry_id' => 'required|integer|exists:laundries,id',
        ]);

        $kabupaten = Kabupaten::create([
            'kabupaten' => $request->kabupaten,
            'laundry_id' => $request->laundry_id,
        ]);

        return response()->json([
            'message' => 'Kabupaten added successfully',
            'kabupaten' => $kabupaten,
        ], 201);
    }

    public function updateKabupaten(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:kabupatens,id',
            'kabupaten' => 'sometimes|required|string|max:255',
            'laundry_id' => 'sometimes|required|integer|exists:laundries,id',
        ]);

        $kabupaten = Kabupaten::find($request->id);
        if (!$kabupaten) {
            return response()->json([
                'message' => 'Kabupaten not found',
            ], 404);
        }

        $kabupaten->update($request->all());

        return response()->json([
            'message' => 'Kabupaten updated successfully',
            'kabupaten' => $kabupaten,
        ], 200);
    }

    public function deleteKabupaten($id)
    {
        $kabupaten = Kabupaten::find($id);
        if (!$kabupaten) {
            return response()->json([
                'message' => 'Kabupaten not found',
            ], 404);
        }

        $kabupaten->delete();

        return response()->json([
            'message' => 'Kabupaten deleted successfully',
        ], 200);
    }

    public function getKabupatens(Request $request)
    {
        $laundry_id = $request->input('laundry_id');

        if ($laundry_id) {
            $kabupatens = Kabupaten::where('laundry_id', $laundry_id)->get();
        } else {
            $kabupatens = Kabupaten::all();
        }

        if ($kabupatens->isEmpty()) {
            return response()->json([
                'message' => 'No kabupatens found',
            ], 200);
        }

        return response()->json([
            'message' => 'Kabupatens list',
            'data' => $kabupatens,
        ], 200);
    }

    public function getKabupaten($id)
    {
        $kabupaten = Kabupaten::find($id);
        if (!$kabupaten) {
            return response()->json([
                'message' => 'Kabupaten not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Kabupaten details',
            'data' => $kabupaten,
        ], 200);
    }
}
