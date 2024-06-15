<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function index()
    {
        // average 24 hours consumption rate of all non-generation sensors
        $from = now()->subDay();
        $to = now();

        $sensorData = SensorData::where('is_generation_sensor', false)
            ->whereBetween('created_at', [$from, $to])
            ->get();

        $generationData = SensorData::where('is_generation_sensor', true)
            ->whereBetween('created_at', [$from, $to])
            ->get();


        return view('dashboard',compact('sensorData', 'generationData'));
        
    }

    public function sensors()
    {
        $nodes = SensorData::select('node_id')->distinct()->get();
        \Log::info($nodes);
        return view('sensors', compact('nodes'));
    }

    public function viewSensor($nodeId)
    {
        $sensorData = SensorData::where('node_id', $nodeId)->get();
        return view('sensor', compact('sensorData'));
    }
}
