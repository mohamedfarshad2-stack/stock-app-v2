<?php


// app/Imports/UsersImport.php
namespace App\Imports;

use App\Models\Data; // or App\Data on older apps
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
     * Map each Excel row to a Data model.
     * Adjust $row indexes to match your sheet.
     */
    public function model(array $row)
    {
        // Example: expect columns:
        // [0]=tracking, [1]=name, [2]=address, [3]=district, [4]=phone, [5]=item_code, [8]=price, [9]=note
        return new Data([
            'tracking_number' => $row[0] ?? null,
            'name'            => $row[1] ?? null,
            'address'         => $row[2] ?? null,
            'district'        => $row[3] ?? null,
            'phone_number'    => $row[4] ?? null,
            'item_code'       => $row[5] ?? null,
            'price'           => isset($row[8]) ? (float)$row[8] : null,
            'note'            => $row[9] ?? null,
        ]);
    }
}
