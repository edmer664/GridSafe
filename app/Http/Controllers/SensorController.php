<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;

class SensorController extends Controller
{
    // sensor receiver
    public function store(Request $request)
    {
        $data = $request->all();
        $sensorData = new SensorData();
        $sensorData->voltage = $data['voltage'];
        $sensorData->ampere = $data['ampere'];
        $sensorData->equipment_temperature = $data['equipment_temp'];
        $sensorData->ambient_temperature = $data['ambient_temp'];
        $sensorData->is_generation_sensor = $data['is_generation'];
        $sensorData->node_id = $data['node_id'];
        $sensorData->save();
    
        return response()->json(['message' => 'Data saved'], 200);
        
    }

    // Log receiver
    public function log(Request $request)
    {
        \Log::info($request->all());
        return response()->json(['message' => 'Logged'], 200);
    }
}   
