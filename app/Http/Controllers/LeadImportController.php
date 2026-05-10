<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadOrder;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class LeadImportController extends Controller
{
    public function showUploadPage()
    {
        return view('import');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $file = $request->file('file');
        $rows = Excel::toArray([], $file)[0];

        foreach ($rows as $row) {
            if (empty($row[1])) continue;

            $lead = Lead::firstOrCreate(
                ['phone' => trim($row[1])],
                ['name' => $row[0] ?? null]
            );

            if (!empty($row[2])) {
                LeadOrder::create([
                    'lead_id' => $lead->id,
                    'status' => trim($row[2])
                ]);
            }
        }

        return back()->with('success', 'Imported Successfully!');
    }
     public function showSearchPage()
    {
        return view('search');
    }
    public function search(Request $request)
    {
        $search = $request->q;

        $lead = Lead::where('phone', $search)
            ->orWhere('name', 'LIKE', "%$search%")
            ->first();

        if (!$lead) {
            return back()->with('error', 'Lead not found');
        }

        $orders = LeadOrder::where('lead_id', $lead->id)->get();

        $total = $orders->count();
        $D = $orders->where('status', 'D')->count();
        $R = $orders->where('status', 'R')->count();
        $E = $orders->where('status', 'E')->count();

        return view('result', compact('lead', 'total', 'D', 'R', 'E'));
    }
    public function index()
    {
        return view('dashboard', [
            'total_orders' => LeadOrder::count(),
            'delivered' => LeadOrder::where('status', 'D')->count(),
            'returned' => LeadOrder::where('status', 'R')->count(),
            'error' => LeadOrder::where('status', 'E')->count(),
        ]);
    }
}