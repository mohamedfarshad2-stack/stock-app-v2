<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class HELOSMoneyRecordTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            ['Date', 'Business Unit', 'Category', 'Type', 'Payment Status', 'Payment Method', 'Supplier / Customer', 'Amount', 'Paid Amount', 'Due Date', 'Reference Number', 'Notes'],
            ['2026-05-01', 'Horns England', 'COD Revenue', 'income', 'paid', 'bank_transfer', 'COD Settlement', '150000', '150000', '', 'INV-1001', 'Monthly COD settlement'],
            ['2026-05-02', 'Horns England', 'Courier Cost', 'expense', 'paid', 'bank_transfer', 'Courier Partner', '32000', '32000', '', 'BILL-778', 'Weekly courier invoice'],
            ['2026-05-03', 'Horns England', 'Rent', 'expense', 'paid', 'bank_transfer', 'Landlord', '45000', '45000', '', 'RENT-0526', 'Fixed overhead'],
            ['2026-05-04', 'Horns England', 'Salaries', 'expense', 'paid', 'bank_transfer', 'Operations Team', '210000', '210000', '', 'SAL-0526', 'Variable overhead'],
        ];
    }
}
