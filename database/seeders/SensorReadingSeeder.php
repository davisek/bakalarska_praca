<?php

namespace Database\Seeders;

use App\Models\SensorReading;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SensorReadingSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();

        $lastYear = $today->copy()->subYear();

        for ($date = $lastYear; $date <= $today; $date->addDay()) {
            $month = $date->month;

            if (in_array($month, [12, 1, 2])) {
                $temperature = rand(-30, 5);
                $humidity = rand(30, 50);
                $pressure = rand(1000, 1020);
            } elseif (in_array($month, [6, 7, 8])) {
                $temperature = rand(20, 41);
                $humidity = rand(60, 80);
                $pressure = rand(1005, 1025);
            } else {
                $temperature = rand(8, 20);
                $humidity = rand(50, 70);
                $pressure = rand(1010, 1030);
            }

            SensorReading::create([
                'temperature' => $temperature,
                'humidity' => $humidity,
                'pressure' => $pressure,
                'created_at' => $date
            ]);
        }
    }
}
