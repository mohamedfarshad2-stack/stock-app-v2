<?php

// app/Exports/UsersExport.php
namespace App\Exports;

use App\Models\Data; // or App\Data
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    public function collection()
    {
        // Export whatever you need; Data::all() exports your imported rows
        return Data::all();
    }
}
