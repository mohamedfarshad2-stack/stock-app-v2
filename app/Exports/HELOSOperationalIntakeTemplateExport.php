<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class HELOSOperationalIntakeTemplateExport implements FromArray
{
    public function __construct(private string $businessType = 'general')
    {
    }

    public function array(): array
    {
        $header = [
            'Order No',
            'Order Date',
            'Customer Name',
            'Phone',
            'Address',
            'Products',
            'COD Amount',
            'Employee',
            'Operational Status',
            'Failure Reason',
            'Remarks',
            'Courier',
            'Tracking No',
            'Confirmation State',
            'Service/Channel',
        ];

        $codRow = ['ORD-1001', '2026-05-16', 'Customer A', '0771234567', 'Colombo 05', 'SKU-1001 x1', 4200, 'Agent 01', 'pending', '', 'Need reconfirm', 'Courier X', 'TRK123', 'confirmed', 'COD'];
        $generalRow = ['ORD-2001', '2026-05-16', 'Customer B', '0712345678', 'Main Street', 'ITEM-01 x2', 8500, 'Ops 01', 'pending', '', 'Initial intake', 'Courier Y', 'TRK200', 'confirmed', 'Retail'];

        return [
            $header,
            $this->businessType === 'cod' ? $codRow : $generalRow,
        ];
    }
}
