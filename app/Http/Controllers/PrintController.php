<?php

// app/Http/Controllers/PrintController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\UsersImport;
use App\Exports\UsersExport;
use App\Models\Data; // or App\Data
use Maatwebsite\Excel\Facades\Excel;

class PrintController extends Controller
{
    // List & upload page
    public function index(Request $request)
    {
        // dd("cds");
        $items = Data::latest()->paginate(50);
        return view('print.index', compact('items'));
    }

    // Import .xlsx/.csv
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required','file','mimes:xlsx,csv,xls'],
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return redirect()->route('print.index')->with('ok', 'Imported successfully.');
    }

    // Export (xlsx)
    public function export()
    {
        return Excel::download(new UsersExport, 'export.xlsx');
    }

    // Delete all (or implement selective delete)
    public function delete(Request $request)
    {
        Data::truncate();
        return redirect()->route('print.index')->with('ok', 'Cleared.');
    }

    // Printer-friendly page (auto window.print in view)
    public function bulk()
    {
        $particpants = Data::orderBy('id')->get();
        return view('print.bulk', compact('particpants'));
    }
}
