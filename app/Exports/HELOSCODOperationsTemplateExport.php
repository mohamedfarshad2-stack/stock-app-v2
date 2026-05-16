<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class HELOSCODOperationsTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            ['Operation Date', 'Business Unit', 'SKU', 'Product Name', 'Confirmed Quantity', 'Selling Price', 'Product Cost', 'Courier Cost', 'Expected Return %', 'Notes'],
            ['2026-05-10', 'Horns England', 'SKU-1001', 'Sample Product A', '40', '4200', '2100', '450', '18', 'Initial confirmed queue'],
            ['2026-05-10', 'Horns England', 'SKU-1002', 'Sample Product B', '25', '3900', '1900', '500', '22', 'Ready for dispatch decision'],
        ];
    }
}
