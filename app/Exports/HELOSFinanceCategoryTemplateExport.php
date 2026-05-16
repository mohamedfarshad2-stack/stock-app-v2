<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class HELOSFinanceCategoryTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            ['Name', 'Code', 'Type', 'Parent Category', 'Is Active'],
            ['Raw Materials', 'RAW_MATERIALS', 'expense', '', '1'],
            ['Adhesive', 'ADHESIVE', 'expense', 'Raw Materials', '1'],
            ['Wholesale Sales', 'WHOLESALE_SALES', 'income', '', '1'],
        ];
    }
}
