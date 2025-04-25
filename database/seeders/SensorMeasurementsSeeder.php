<?php

namespace Database\Seeders;

use App\Enums\Sensor\ColorClass;
use App\Enums\User\LocaleEnum;
use App\Models\Sensor;
use App\Models\SensorGroup;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Locale;

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
        $lighning = SensorGroup::create([
            'group_name' => 'Lightning',
            'group_value' => 'lightning',
            'image_path' => $this->storeImage($assetPathGroupImage, 'lightning-image.jpg'),
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
            'sensor_name' => 'Water sensor',
            'type' => 'water',
            'display_name' => 'Water',
            'unit_of_measurement' => null,
            'is_output_binary' => true,
            'sensor_group_id' => $outdoor->id,
            'color_class' => ColorClass::HUMIDITY_CARD,
            'image_path' => $this->storeImage($assetPathSensorImage, 'water-image.jpg'),
            'icon_path' => $this->storeImage($assetPathSensorIcon, 'water.png'),
        ]);
        Sensor::create([
            'sensor_name' => 'BME680',
            'type' => 'temperature-bme',
            'display_name' => 'Room Temperature',
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
            'display_name' => 'Room Humidity',
            'unit_of_measurement' => '%',
            'is_output_binary' => false,
            'sensor_group_id' => $indoor->id,
            'color_class' => ColorClass::HUMIDITY_CARD,
            'image_path' => $this->storeImage($assetPathSensorImage, 'humidity-image.jpg'),
            'icon_path' => $this->storeImage($assetPathSensorIcon, 'humidity.png'),
        ]);
        Sensor::create([
            'sensor_name' => 'BME680',
            'type' => 'pressure-bme',
            'display_name' => 'Room Pressure',
            'unit_of_measurement' => 'hPa',
            'is_output_binary' => false,
            'sensor_group_id' => $indoor->id,
            'color_class' => ColorClass::PRESSURE_CARD,
            'image_path' => $this->storeImage($assetPathSensorImage, 'pressure-image.jpg'),
            'icon_path' => $this->storeImage($assetPathSensorIcon, 'pressure.png'),
        ]);
        Sensor::create([
            'sensor_name' => 'BME680',
            'type' => 'iaq-bme',
            'display_name' => 'Indoor Air Quality',
            'unit_of_measurement' => '%',
            'is_output_binary' => false,
            'sensor_group_id' => $indoor->id,
            'color_class' => ColorClass::AIR_CARD,
            'image_path' => $this->storeImage($assetPathSensorImage, 'iaq-image.png'),
            'icon_path' => $this->storeImage($assetPathSensorIcon, 'iaq.png'),
        ]);
        Sensor::create([
            'sensor_name' => 'CJMCU-3935',
            'type' => 'lightning',
            'display_name' => 'Lightning',
            'unit_of_measurement' => null,
            'is_output_binary' => true,
            'sensor_group_id' => $lighning->id,
            'color_class' => ColorClass::LIGHT_CARD,
            'image_path' => $this->storeImage($assetPathSensorImage, 'lightning-image.jpg'),
            'icon_path' => $this->storeImage($assetPathSensorIcon, 'lightning.png'),
        ]);
        Sensor::create([
            'sensor_name' => 'CJMCU-3935',
            'type' => 'distance',
            'display_name' => 'Distance',
            'unit_of_measurement' => 'km',
            'is_output_binary' => false,
            'sensor_group_id' => $lighning->id,
            'color_class' => ColorClass::LIGHT_CARD,
            'image_path' => $this->storeImage($assetPathSensorImage, 'lightning-image.jpg'),
            'icon_path' => $this->storeImage($assetPathSensorIcon, 'lightning.png'),
        ]);
        Sensor::create([
            'sensor_name' => 'CJMCU-3935',
            'type' => 'energy',
            'display_name' => 'Energy',
            'unit_of_measurement' => null,
            'is_output_binary' => false,
            'sensor_group_id' => $lighning->id,
            'color_class' => ColorClass::LIGHT_CARD,
            'image_path' => $this->storeImage($assetPathSensorImage, 'lightning-image.jpg'),
            'icon_path' => $this->storeImage($assetPathSensorIcon, 'lightning.png'),
        ]);
        Sensor::create([
            'sensor_name' => 'CJMCU-3935',
            'type' => 'noise',
            'display_name' => 'Noise',
            'unit_of_measurement' => null,
            'is_output_binary' => true,
            'sensor_group_id' => $lighning->id,
            'color_class' => ColorClass::LIGHT_CARD,
            'image_path' => $this->storeImage($assetPathSensorImage, 'noise-image.jpg'),
            'icon_path' => $this->storeImage($assetPathSensorIcon, 'noise.png'),
        ]);

        $this->createAdminUser();
    }

    private function storeImage(string $assetPath, string $image)
    {
        if (file_exists($assetPath . '/' . $image)) {
            return Storage::disk('public')->putFile('images/sensors', new File($assetPath . '/' . $image));
        }
    }

    private function createAdminUser()
    {
        User::create([
            'name' => 'Admin',
            'surname' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => '12345678',
            'locale' => LocaleEnum::SLOVAK,
            'is_admin' => true,
            'dark_mode' => true,
            'email_verified_at' => now(),
            'auth_key' => bin2hex(random_bytes(32)),
        ]);
    }
}
