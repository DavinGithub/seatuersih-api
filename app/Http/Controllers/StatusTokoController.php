<?php

namespace App\Http\Controllers;

use App\Models\StatusToko;
use Illuminate\Http\Request;

class StatusTokoController extends Controller
{
    // Method untuk membuat status toko baru
    public function store(Request $request)
    {
        // Menambahkan nilai default 'is_open' jika tidak ada dalam request
        $request->merge([
            'is_open' => $request->has('is_open') ? filter_var($request->input('is_open'), FILTER_VALIDATE_BOOLEAN) : false,
        ]);

        // Validasi request
        $validatedData = $request->validate([
            'is_open' => 'required|boolean',
        ]);

        // Membuat status toko baru dengan data yang divalidasi
        $statusToko = StatusToko::create($validatedData);

        // Mengembalikan response dengan data status toko yang baru saja dibuat
        return response()->json(['data' => $statusToko], 201);
    }

    // Method untuk mengupdate status toko yang sudah ada
    public function update($id)
    {
        $statusToko = StatusToko::find($id);

        if (is_null($statusToko)) {
            return response()->json(['message' => 'Store status not found'], 404);
        }

        // Toggle status is_open (dari true ke false atau sebaliknya)
        $statusToko->is_open = !$statusToko->is_open;
        $statusToko->save();

        return response()->json(['data' => $statusToko], 200);
    }

    // Method untuk mengambil status toko berdasarkan ID
    public function show($id)
    {
        $statusToko = StatusToko::find($id);

        if (is_null($statusToko)) {
            return response()->json(['message' => 'Store status not found'], 404);
        }

        return response()->json(['data' => $statusToko], 200);
    }

    // Method untuk mengambil semua status toko
    public function index()
    {
        $statusTokos = StatusToko::all();

        return response()->json(['data' => $statusTokos], 200);
    }
}
