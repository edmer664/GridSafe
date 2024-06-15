<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    use HasFactory;

    protected $fillable = [
        'voltage',
        'ampere',
        'equipment_temperature',
        'ambient_temperature',
        'is_generation_sensor',
        'node_id',
    ];

    protected $appends = ['power'];

    protected $casts = [
        'is_generation_sensor' => 'boolean',
    ];


    public function getPowerAttribute()
    {
        return $this->calculatePower();
    }

    public function calculatePower()
    {
        return $this->voltage * $this->ampere;
    }

    /**
     * returns in kWh
     */
    public static function getConsumptionRate($nodeId, $from, $to)
    {
        $data = self::where('node_id', $nodeId)
            ->whereBetween('created_at', [$from, $to])
            ->get();

        // calculate total power
        $totalPower = 0;

        foreach ($data as $d) {
            $totalPower += $d->calculatePower();
        }

        // convert to kWh
        return $totalPower / 1000;

    }


    public static function averageVoltage($nodeId, $start, $end)
    {
        $data = self::where('node_id', $nodeId)
            ->whereBetween('created_at', [$start, $end])
            ->get();
            \Log::info($data);

        return $data->avg('voltage');
    }

    public static function averageAmpere($nodeId, $start, $end)
    {
        $data = self::where('node_id', $nodeId)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        return $data->avg('ampere');
    }

    public static function averageEquipmentTemperature($nodeId, $start, $end)
    {
        $data = self::where('node_id', $nodeId)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        return $data->avg('equipment_temperature');
    }

    public static function averageAmbientTemperature($nodeId, $start, $end)
    {
        $data = self::where('node_id', $nodeId)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        return $data->avg('ambient_temperature');
    }


    public static function hasAnomaly($nodeId)
    {
        
        \Log::info('Checking for anomaly' . $nodeId);
        // check 5 min average temp, voltage, ampere
        $from = now()->subMinutes(5);
        $to = now();

        $avgVoltage = self::averageVoltage($nodeId, $from, $to);
        $avgAmpere = self::averageAmpere($nodeId, $from, $to);
        $avgEquipmentTemp = self::averageEquipmentTemperature($nodeId, $from, $to);
        $avgAmbientTemp = self::averageAmbientTemperature($nodeId, $from, $to);

        \Log::info('Average voltage: ' . $avgVoltage);
        \Log::info('Average ampere: ' . $avgAmpere);
        \Log::info('Average equipment temp: ' . $avgEquipmentTemp);
        \Log::info('Average ambient temp: ' . $avgAmbientTemp);

        if ($avgVoltage > 241 || $avgVoltage < 220) {
            \Log::info('Category 1');
            return true;
        }

        if ($avgAmpere > 11) {
            \Log::info('Category 2');
            return true;
        }

        if ($avgEquipmentTemp > 80) {
            \Log::info('Category 3');
            return true;
        }

        if ($avgAmbientTemp > 35) {
            \Log::info('Category 4');
            return true;
        }

        \Log::info('No anomaly detected');

        return false;


    }

    public static function isLowGeneration($nodeId)
    {
        $from = now()->subMinutes(5);
        $to = now();

        $data = self::where('node_id', $nodeId)
            ->whereBetween('created_at', [$from, $to])
            ->get();

        $totalPower = 0;

        foreach ($data as $d) {
            $totalPower += $d->calculatePower();
        }

        if ($totalPower < 1000) {
            return true;
        }

        return false;
    }


    public static function isGenerationSensor($nodeId)
    {
        return self::where('node_id', $nodeId)
            ->where('is_generation_sensor', true)
            ->exists();
    }



}
