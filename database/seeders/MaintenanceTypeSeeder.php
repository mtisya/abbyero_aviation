<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaintenanceType;

class MaintenanceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [

            [
                'name'=>'Oil Change',
                'code'=>'OIL',
                'category'=>'Engine',
                'default_interval_hours'=>25
            ],

            [
                'name'=>'50 Hour Inspection',
                'code'=>'50HR',
                'category'=>'Inspection',
                'default_interval_hours'=>50
            ],

            [
                'name'=>'100 Hour Inspection',
                'code'=>'100HR',
                'category'=>'Inspection',
                'default_interval_hours'=>100
            ],

            [
                'name'=>'Annual Inspection',
                'code'=>'ANNUAL',
                'category'=>'Inspection',
                'default_interval_days'=>365
            ],

            [
                'name'=>'VOR Check',
                'code'=>'VOR',
                'category'=>'Avionics',
                'default_interval_days'=>30
            ],

            [
                'name'=>'ELT Inspection',
                'code'=>'ELT',
                'category'=>'Emergency',
                'default_interval_days'=>365
            ],

            [
                'name'=>'ELT Battery Replacement',
                'code'=>'ELTBAT',
                'category'=>'Emergency',
                'default_interval_days'=>730
            ],

            [
                'name'=>'Pitot Static Check',
                'code'=>'PITOT',
                'category'=>'Avionics',
                'default_interval_days'=>730
            ],

            [
                'name'=>'Transponder Inspection',
                'code'=>'XPDR',
                'category'=>'Avionics',
                'default_interval_days'=>730
            ],

            [
                'name'=>'Compass Swing',
                'code'=>'COMPASS',
                'category'=>'Avionics'
            ],

            [
                'name'=>'Engine Overhaul',
                'code'=>'ENGOH',
                'category'=>'Engine',
                'default_interval_hours'=>2000
            ],

            [
                'name'=>'Propeller Inspection',
                'code'=>'PROP',
                'category'=>'Propeller'
            ],

        ];

        foreach ($types as $type) {

            MaintenanceType::updateOrCreate(
                ['code'=>$type['code']],
                $type
            );

        }
    }
}