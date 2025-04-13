<?php

namespace Database\Seeders;

use App\Enums\Sensor\ColorClass;
use App\Models\Sensor;
use App\Models\SensorGroup;
use App\Models\User;
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

        $outdoor = SensorGroup::create([
            'group_name' => 'Outdoor',
            'group_value' => 'outdoor',
            'image_path' => $this->storeImage($assetPathGroupImage, 'outdoor-image.jpg'),
        ]);
        $indoor = SensorGroup::create([
            'group_name' => 'Indoor',
            'group_value' => 'indoor',
            'image_path' => $this->storeImage($assetPathGroupImage, 'room-image.jpg'),
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
            'is_output_binary' => false,
            'sensor_group_id' => $outdoor->id,
            'color_class' => ColorClass::TEMPERATURE_CARD,
            'image_path' => $this->storeImage($assetPathSensorImage, 'temperature-image.jpg'),
            'icon_path' => $this->storeImage($assetPathSensorIcon, 'temperature.png'),
        ]);
        Sensor::create([
            'sensor_name' => 'DHT22',
            'type' => 'humidity',
            'display_name' => 'Humidity',
            'unit_of_measurement' => '%',
            'is_output_binary' => false,
            'sensor_group_id' => $outdoor->id,
            'color_class' => ColorClass::HUMIDITY_CARD,
            'image_path' => $this->storeImage($assetPathSensorImage, 'humidity-image.jpg'),
            'icon_path' => $this->storeImage($assetPathSensorIcon, 'humidity.png'),
        ]);
        Sensor::create([
            'sensor_name' => 'BME680',
            'type' => 'pressure-bme',
            'display_name' => 'Indoor Pressure',
            'unit_of_measurement' => 'hPa',
            'is_output_binary' => false,
            'sensor_group_id' => $indoor->id,
            'color_class' => ColorClass::DEFAULT_CARD,
            'image_path' => $this->storeImage($assetPathSensorImage, 'pressure-image.jpg'),
            'icon_path' => $this->storeImage($assetPathSensorIcon, 'pressure.png'),
        ]);
        Sensor::create([
            'sensor_name' => 'BME680',
            'type' => 'temperature-bme',
            'display_name' => 'Indoor Temperature',
            'unit_of_measurement' => '°C',
            'is_output_binary' => false,
            'sensor_group_id' => $indoor->id,
            'color_class' => ColorClass::TEMPERATURE_CARD,
            'image_path' => $this->storeImage($assetPathSensorImage, 'temperature-image.jpg'),
            'icon_path' => $this->storeImage($assetPathSensorIcon, 'temperature.png'),
        ]);
        Sensor::create([
            'sensor_name' => 'BME680',
            'type' => 'humidity-bme',
            'display_name' => 'Indoor Humidity',
            'unit_of_measurement' => '%',
            'is_output_binary' => false,
            'sensor_group_id' => $indoor->id,
            'color_class' => ColorClass::HUMIDITY_CARD,
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
