<?php

namespace App\Http\Controllers;

use App\Models\TiktokSale;
use App\Models\UploadedFile;
use Illuminate\Http\Request;
use League\Csv\Reader;

class DashboardController extends Controller
{
    public function index()
    {
        // Get all sales
        $sales = TiktokSale::all();

        // Get Top 15 categories by GMV for Horizontal Bar Chart
        $top15ByGMV = TiktokSale::selectRaw('campaign, SUM(direct_gmv) as total_gmv, COUNT(*) as count')
            ->groupBy('campaign')
            ->orderByDesc('total_gmv')
            ->limit(15)
            ->get();

        // Get data grouped by campaign for Scatter Plot (Customers vs GMV)
        $scatterData = TiktokSale::selectRaw('campaign, AVG(customers) as avg_customers, AVG(direct_gmv) as avg_gmv')
            ->groupBy('campaign')
            ->get();

        // Get data for Treemap (Customers size, GMV color)
        $treemapData = TiktokSale::selectRaw('campaign, SUM(customers) as total_customers, SUM(direct_gmv) as total_gmv')
            ->groupBy('campaign')
            ->orderByDesc('total_gmv')
            ->limit(20)
            ->get();

        // Get Top 20 categories + Others for Grouped Bar Chart
        $categoryData = TiktokSale::selectRaw('campaign, SUM(direct_gmv) as total_gmv, SUM(customers) as total_customers, COUNT(*) as count')
            ->groupBy('campaign')
            ->orderByDesc('total_gmv')
            ->get();

        // Process for Top 20 + Others
        $top20 = $categoryData->take(20);
        $othersGMV = $categoryData->skip(20)->sum('total_gmv');
        $othersCustomers = $categoryData->skip(20)->sum('total_customers');

        if ($othersGMV > 0 || $othersCustomers > 0) {
            $top20->push((object) [
                'campaign' => 'Others',
                'total_gmv' => $othersGMV,
                'total_customers' => $othersCustomers,
                'count' => $categoryData->skip(20)->count(),
            ]);
        }

        $groupedChartData = $top20;

        return view('dashboard', compact(
            'sales',
            'top15ByGMV',
            'scatterData',
            'treemapData',
            'groupedChartData'
        ));
    }

    public function upload(Request $request)
    {
        set_time_limit(300); // 5 menit timeout

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        try {
            $file = $request->file('csv_file');
            $originalName = $file->getClientOriginalName();

            // Store file
            $filePath = $file->store('uploads', 'public');
            $fileSize = $file->getSize();

            // Parse and insert data
            $csv = Reader::createFromPath($file->getRealPath(), 'r');
            $csv->setHeaderOffset(0);

            $records = [];
            $batchSize = 100;
            $count = 0;

            foreach ($csv as $row) {
                $records[] = [
                    'campaign' => $row['Campaign'] ?? null,
                    'date' => ! empty($row['Date']) ? date('Y-m-d', strtotime($row['Date'])) : null,
                    'time' => $row['Time'] ?? null,
                    'direct_gmv' => ! empty($row['Direct GMV']) ? str_replace(',', '', $row['Direct GMV']) : 0,
                    'items_sold' => $row['Items sold'] ?? 0,
                    'customers' => $row['Customers'] ?? 0,
                    'viewers' => $row['Viewers'] ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Insert batch setiap 100 records
                if (count($records) >= $batchSize) {
                    TiktokSale::insert($records);
                    $count += count($records);
                    $records = [];
                }
            }

            // Insert sisa records
            if (! empty($records)) {
                TiktokSale::insert($records);
                $count += count($records);
            }

            // Create file record
            UploadedFile::create([
                'user_id' => auth()->id(),
                'file_name' => basename($filePath),
                'file_path' => $filePath,
                'original_name' => $originalName,
                'file_size' => $fileSize,
                'row_count' => $count,
                'status' => 'processed',
            ]);

            return redirect('/dashboard')->with('success', "Berhasil upload $count data penjualan!");
        } catch (\Exception $e) {
            return back()->with('error', 'Error: '.$e->getMessage())->withInput();
        }
    }
}
