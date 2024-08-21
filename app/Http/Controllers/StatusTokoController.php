<?php

namespace App\Http\Controllers;

use App\Models\StatusToko;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StatusTokoController extends Controller
{
    public function index()
    {
        $timezone = 'Asia/Jakarta'; 
        $today = Carbon::now($timezone)->locale('id')->dayName;
        $currentTime = Carbon::now($timezone)->format('H:i:s'); // Get the current time
    
        $statusTokoList = StatusToko::all();
    
        foreach ($statusTokoList as $statusToko) {
            Log::info('Current Store Status:', [
                'id' => $statusToko->id,
                'is_open' => $statusToko->is_open,
                'start_time' => $statusToko->start_time,
                'end_time' => $statusToko->end_time,
                'temporary_closure_duration' => $statusToko->temporary_closure_duration,
                'current_time' => $currentTime
            ]);
    
            $startTime_C = strtotime($statusToko->start_time);
            $endTime_C = strtotime($statusToko->end_time);
            $currentTime_C = strtotime($currentTime);

            if ($statusToko->days && stripos($statusToko->days, $today) !== false) {
                if ($currentTime_C >= $startTime_C && $currentTime_C <= $endTime_C) {
                    $statusToko->is_open = false;
                    if ($statusToko->temporary_closure_duration) {
                        $duration = (int) $statusToko->temporary_closure_duration;
                        $closureEndTime = Carbon::parse($statusToko->updated_at)->addMinutes($duration);
    
                        if (Carbon::now($timezone)->greaterThanOrEqualTo($closureEndTime)) {
                            $statusToko->temporary_closure_duration = 0;
                            $statusToko->is_open = true;
                        }
                    } else {
                        $statusToko->is_open = true;
                    }
                } else {
                    $statusToko->is_open = false;
                }
            }

            Log::info('Updated Store Status:', [
                'id' => $statusToko->id,
                'is_open' => $statusToko->is_open,
                'start_time' => $statusToko->start_time,
                'end_time' => $statusToko->end_time,
                'temporary_closure_duration' => $statusToko->temporary_closure_duration,
                'current_time' => $currentTime
            ]);
    
            $statusToko->save();
        }
    
        return response()->json(['data' => $statusTokoList], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'is_open' => 'nullable|boolean',
            'days' => 'nullable|string',
            'start_time' => 'nullable|date_format:H:i:s',
            'end_time' => 'nullable|date_format:H:i:s',
            'temporary_closure_duration' => 'nullable|integer',
        ]);

        $statusToko = StatusToko::create($validatedData);

        return response()->json(['data' => $statusToko], 201);
    }

    public function show($id)
    {
        $statusToko = StatusToko::find($id);

        if (is_null($statusToko)) {
            return response()->json(['message' => 'Store status not found'], 404);
        }

        return response()->json(['data' => $statusToko], 200);
    }

    public function update(Request $request, $id)
    {
        Log::info('Update Store Status Request: ', $request->all());

        $validatedData = $request->validate([
            'is_open' => 'nullable|boolean',
            'days' => 'nullable|string',
            'start_time' => 'nullable|date_format:H:i:s',
            'end_time' => 'nullable|date_format:H:i:s',
            'temporary_closure_duration' => 'nullable|integer',
        ]);

        $statusToko = StatusToko::find($id);

        if (is_null($statusToko)) {
            return response()->json(['message' => 'Store status not found'], 404);
        }

        $statusToko->update($validatedData);

        return response()->json(['data' => $statusToko], 200);
    }

    public function destroy($id)
    {
        $statusToko = StatusToko::find($id);

        if (is_null($statusToko)) {
            return response()->json(['message' => 'Store status not found'], 404);
        }

        $statusToko->delete();

        return response()->json(['message' => 'Store status berhasil dihapus'], 200);
    }
}
