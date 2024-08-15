<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{   
        public function addKecamatan(Request $request)
        {
            $request->validate([
                'kecamatan' => 'required|string|max:255',
                'kabupaten_id' => 'required|integer|exists:kabupatens,id',
            ]);
    
            $kecamatan = Kecamatan::create([
                'kecamatan' => $request->kecamatan,
                'kabupaten_id' => $request->kabupaten_id,
            ]);
    
            return response()->json([
                'message' => 'K ecamatan added successfully',
                'kecamatan' => $kecamatan,
            ], 201);
        }
    
        public function updateKecamatan(Request $request)
        {
            $request->validate([
                'id' => 'required|integer|exists:kecamatans,id',
                'kecamatan' => 'sometimes|required|string|max:255',
                'kabupaten_id' => 'sometimes|required|integer|exists:kabupatens,id',
            ]);
    
            $kecamatan = Kecamatan::find($request->id);
            if (!$kecamatan) {
                return response()->json([
                    'message' => 'Kecamatan not found',
                ], 404);
            }
    
            $kecamatan->update($request->all());
    
            return response()->json([
                'message' => 'Kecamatan updated successfully',
                'kecamatan' => $kecamatan,
            ], 200);
        }

    public function deleteKecamatan($id)
    {
        $kecamatan = Kecamatan::find($id);
        if (!$kecamatan) {
            return response()->json([
                'message' => 'Kecamatan not found',
            ], 404);
        }

        $kecamatan->delete();

        return response()->json([
            'message' => 'Kecamatan deleted successfully',
        ], 200);
    }

    public function getKecamatans(Request $request)
    {
        $laundry_id = $request->input('laundry_id');

        if ($laundry_id) {
            $kecamatans = Kecamatan::where('laundry_id', $laundry_id)->get();
        } else {
            $kecamatans = Kecamatan::all();
        }

        if ($kecamatans->isEmpty()) {
            return response()->json([
                'message' => 'No kecamatans found',
            ], 200);
        }

        return response()->json([
            'message' => 'Kecamatans list',
            'data' => $kecamatans,
        ], 200);
    }

    public function getKecamatan($id)
    {
        $kecamatan = Kecamatan::find($id);
        if (!$kecamatan) {
            return response()->json([
                'message' => 'Kecamatan not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Kecamatan details',
            'data' => $kecamatan,
        ], 200);
    }
    public function getKecamatanByKabupatenId($kabupaten_id)
    {
        $kecamatans = Kecamatan::where('kabupaten_id', $kabupaten_id)->get();

        if ($kecamatans->isEmpty()) {
            return response()->json([
                'message' => 'No kecamatans found for the given kabupaten_id',
            ], 200);
        }

        return response()->json([
            'message' => 'Kecamatans list for the given kabupaten_id',
            'data' => $kecamatans,
        ], 200);
    }
}
