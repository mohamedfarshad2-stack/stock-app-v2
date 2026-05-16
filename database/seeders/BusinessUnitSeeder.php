<?php

namespace Database\Seeders;

use App\Models\BusinessUnit;
use Illuminate\Database\Seeder;

class BusinessUnitSeeder extends Seeder
{
    public function run()
    {
        $records = [
            ['name' => 'Horns England', 'code' => 'HORNS', 'type' => 'brand'],
            ['name' => 'ShooHub', 'code' => 'SHOOHUB', 'type' => 'brand'],
            ['name' => 'COD Returns Lanka', 'code' => 'CODRL', 'type' => 'service'],
            ['name' => 'Shared Overhead', 'code' => 'SHARED', 'type' => 'shared'],
        ];

        foreach ($records as $record) {
            BusinessUnit::updateOrCreate(
                ['name' => $record['name']],
                ['code' => $record['code'], 'type' => $record['type'], 'is_active' => true]
            );
        }
    }
}
