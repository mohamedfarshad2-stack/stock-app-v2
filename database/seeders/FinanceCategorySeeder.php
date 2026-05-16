<?php

namespace Database\Seeders;

use App\Models\FinanceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FinanceCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'income' => ['COD Sales', 'Wholesale Sales', 'Recovery Service Income', 'Other Income'],
            'expense' => ['Ads', 'Courier Cost', 'Material Purchase', 'Stitching / Labour', 'Salary', 'Rent', 'Electricity', 'Internet', 'Fuel', 'Packaging', 'Staff Welfare', 'Returns Loss', 'Miscellaneous'],
            'receivable' => ['Wholesale Receivable', 'Client Receivable'],
            'payable' => ['Supplier Payable'],
        ];

        foreach ($categories as $type => $names) {
            foreach ($names as $name) {
                FinanceCategory::updateOrCreate(
                    ['name' => $name, 'type' => $type],
                    ['code' => Str::upper(Str::slug($name, '_')), 'is_active' => true]
                );
            }
        }
    }
}
