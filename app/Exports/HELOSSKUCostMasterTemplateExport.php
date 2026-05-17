<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class HELOSSKUCostMasterTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            [
                'Business Unit',
                'SKU',
                'Product Name',
                'Slipper Type',
                'Default Selling Price',
                'Strap Maker Cost',
                'Stitching Worker Cost',
                'Finishing Worker Cost',
                'Strap Cost',
                'Stitching Cost',
                'Stitching Employee Cost',
                'Upper Layer Cost',
                'Middle Layer Cost',
                'Bottom Layer Cost',
                'Adhesive (Glue) Cost',
                'Stationery Cost',
                'Base Employee Cost',
                'Packing Cost',
                'Courier Cost',
                'Ad Allocation',
                'Return Loss Estimate',
                'Weight',
                'Active (1/0)',
                'Notes',
            ],
            ['Horns England', 'SKU-R-1001', 'Retail Slipper A', 'retail', 4200, 110, 120, 130, 350, 80, 120, 420, 210, 390, 65, 25, 180, 70, 450, 120, 95, 0.65, 1, 'Retail has higher performance-based labor'],
            ['Horns England', 'SKU-W-2001', 'Wholesale Slipper B', 'wholesale', 3600, 85, 90, 100, 330, 70, 90, 390, 200, 370, 60, 20, 130, 65, 450, 95, 85, 0.62, 1, 'Wholesale labor cost lower per unit'],
        ];
    }
}
