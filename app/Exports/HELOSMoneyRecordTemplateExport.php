<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class HELOSMoneyRecordTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            ['Date', 'Business Unit', 'Category', 'Type', 'Payment Status', 'Payment Method', 'Supplier / Customer', 'Amount', 'Paid Amount', 'Due Date', 'Reference Number', 'Notes'],
            ['2026-05-01', 'Horns England', 'Wholesale Sales', 'income', 'paid', 'bank_transfer', 'ABC Wholesale', '150000', '150000', '', 'INV-1001', 'Monthly wholesale settlement'],
            ['2026-05-02', 'Horns England', 'Adhesive', 'expense', 'partial', 'credit', 'Sample Supplier', '25000', '10000', '2026-05-16', 'BILL-778', 'Credit purchase sample'],
        ];
    }
}
