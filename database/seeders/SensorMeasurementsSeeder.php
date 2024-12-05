<?php

namespace Database\Seeders;

use App\Models\Sensor;
use App\Models\SensorGroup;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class SensorMeasurementsSeeder extends Seeder
{
    public function run(): void
    {
        $assetPathGroupImage = resource_path('assets/sensor-group-images');
        $assetPathSensorImage = resource_path('assets/sensor-images');
        $assetPathSensorIcon = resource_path('assets/sensor-icons');

        $commonGroup = SensorGroup::create([
            'group_name' => 'Common',
            'group_value' => 'common',
            'image_path' => $this->storeImage($assetPathGroupImage, 'room-image.jpg'),
        ]);
        $quality = SensorGroup::create([
            'group_name' => 'Quality',
            'group_value' => 'quality',
            'image_path' => $this->storeImage($assetPathGroupImage, 'outdoor-image.jpg'),
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
            'image_path' => $this->storeImage($assetPathSensorImage, 'temperature-image.jpg'),
            'icon_path' => $this->storeImage($assetPathSensorIcon, 'temperature.png'),
        ]);
        Sensor::create([
            'sensor_name' => 'DHT22',
            'type' => 'humidity',
            'display_name' => 'Humidity',
            'unit_of_measurement' => '%',
            'sensor_group_id' => $commonGroup->id,
            'image_path' => $this->storeImage($assetPathSensorImage, 'humidity-image.jpg'),
            'icon_path' => $this->storeImage($assetPathSensorIcon, 'humidity.png'),
        ]);
        Sensor::create([
            'sensor_name' => 'BME680',
            'type' => 'pressure-bme',
            'display_name' => 'BME Pressure',
            'unit_of_measurement' => 'hPa',
            'sensor_group_id' => $quality->id,
            'image_path' => $this->storeImage($assetPathSensorImage, 'pressure-image.jpg'),
            'icon_path' => $this->storeImage($assetPathSensorIcon, 'pressure.png'),
        ]);
        Sensor::create([
            'sensor_name' => 'BME680',
            'type' => 'temperature-bme',
            'display_name' => 'BME Temperature',
            'unit_of_measurement' => '°C',
            'sensor_group_id' => $quality->id,
            'image_path' => $this->storeImage($assetPathSensorImage, 'temperature-image.jpg'),
            'icon_path' => $this->storeImage($assetPathSensorIcon, 'temperature.png'),
        ]);
        Sensor::create([
            'sensor_name' => 'BME680',
            'type' => 'humidity-bme',
            'display_name' => 'BME Humidity',
            'unit_of_measurement' => '%',
            'sensor_group_id' => $quality->id,
            'image_path' => $this->storeImage($assetPathSensorImage, 'humidity-image.jpg'),
            'icon_path' => $this->storeImage($assetPathSensorIcon, 'humidity.png'),
        ]);
    }

    private function storeImage(string $assetPath, string $image)
    {
        if (file_exists($assetPath . '/' . $image)) {
            return Storage::disk('public')->putFile('images/sensors', new File($assetPath . '/' . $image));
        }
    }
}
