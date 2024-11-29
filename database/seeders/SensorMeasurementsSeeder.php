<?php

namespace Database\Seeders;

use App\Models\Measurement;
use App\Models\Sensor;
use App\Models\SensorGroup;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SensorMeasurementsSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();
        $lastYear = $today->copy()->subYear();

        $commonGroup = SensorGroup::create([
            'group_name' => 'Common',
            'group_value' => 'common',
        ]);
        SensorGroup::create([
            'group_name' => 'Quality',
            'group_value' => 'quality',
        ]);
        SensorGroup::create([
            'group_name' => 'Others',
            'group_value' => 'others',
        ]);

        Sensor::create([
            'sensor_name' => 'DHT22',
            'type' => 'temperature',
            'display_name' => 'Temperature',
            'unit_of_measurement' => '°C',
            'sensor_group_id' => $commonGroup->id,
        ]);
        Sensor::create([
            'sensor_name' => 'DHT22',
            'type' => 'humidity',
            'display_name' => 'Humidity',
            'unit_of_measurement' => '%',
            'sensor_group_id' => $commonGroup->id,
        ]);
        Sensor::create([
            'sensor_name' => 'DHT22',
            'type' => 'pressure',
            'display_name' => 'Pressure',
            'unit_of_measurement' => 'hPa',
            'sensor_group_id' => $commonGroup->id,
        ]);

        $sensors = Sensor::all();

        foreach ($sensors as $sensor) {
            $currentDate = $lastYear->copy();

            while ($currentDate <= $today) {
                $month = $currentDate->month;

                if (in_array($month, [12, 1, 2])) {
                    $value = rand(-30, 5);
                } elseif (in_array($month, [6, 7, 8])) {
                    $value = rand(20, 41);
                } else {
                    $value = rand(8, 20);
                }

                Measurement::create([
                    'sensor_id' => $sensor->id,
                    'value' => $value,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ]);

                $currentDate->addDay();
            }
        }
    }
}
