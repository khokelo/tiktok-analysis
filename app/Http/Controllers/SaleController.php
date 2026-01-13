<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::latest()->paginate(10);
        $totalGmv = Sale::sum('direct_gmv');
        $totalItems = Sale::sum('items_sold');

        return view('sales.index', compact('sales', 'totalGmv', 'totalItems'));
    }

    public function import(Request $request)
    {
        $file = $request->file('csv_file');
        $csvData = file_get_contents($file);
        $rows = array_map('str_getcsv', explode("\n", $csvData));
        $header = array_shift($rows);

        foreach ($rows as $row) {
            if (count($row) < 5) {
                continue;
            }
            Sale::create([
                'campaign' => $row[0],
                'day' => $row[1],
                'date' => Carbon::createFromFormat('d/m/Y', $row[2])->format('Y-m-d'),
                'time' => $row[3],
                'direct_gmv' => (float) str_replace(',', '', $row[4]),
                'items_sold' => (int) $row[5],
                // ... Tambahkan kolom lainnya sesuai urutan CSV
            ]);
        }

        return back()->with('success', 'Data berhasil diimpor!');
    }
}
