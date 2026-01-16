<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TiktokSale;
use App\Models\UploadedFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // User stats
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalRegularUsers = User::where('role', 'user')->count();

        // File stats
        $totalFiles = UploadedFile::count();
        $filesProcessed = UploadedFile::where('status', 'processed')->count();
        $filesFailed = UploadedFile::where('status', 'failed')->count();
        $totalFileSize = UploadedFile::sum('file_size');
        $recentFiles = UploadedFile::with('user')->latest()->take(5)->get();

        // Sales stats
        $totalSales = TiktokSale::count();

        // Chart data (last 30 days)
        $salesData = TiktokSale::where('date', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(date) as sale_date'),
                DB::raw('SUM(direct_gmv) as total_gmv'),
                DB::raw('SUM(items_sold) as total_items')
            )
            ->groupBy('sale_date')
            ->orderBy('sale_date', 'asc')
            ->get();

        $chartLabels = $salesData->pluck('sale_date')->map(function ($date) {
            return date('d M', strtotime($date));
        });
        $chartGMV = $salesData->pluck('total_gmv');
        $chartItems = $salesData->pluck('total_items');


        // Top campaigns
        $topCampaigns = TiktokSale::select(
            'campaign',
            DB::raw('SUM(direct_gmv) as total_gmv'),
            DB::raw('AVG(direct_gmv) as avg_gmv')
        )
            ->groupBy('campaign')
            ->orderByDesc('total_gmv')
            ->limit(10)
            ->get();
        $campaignLabels = $topCampaigns->pluck('campaign');
        $campaignGMV = $topCampaigns->pluck('total_gmv');

        // Top upload users
        $topUploadUsers = User::withCount('uploadedFiles')->orderByDesc('uploaded_files_count')->limit(5)->get();
        $userNames = $topUploadUsers->pluck('name');
        $userFiles = $topUploadUsers->pluck('uploaded_files_count');

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalRegularUsers',
            'totalFiles',
            'filesProcessed',
            'filesFailed',
            'totalFileSize',
            'recentFiles',
            'totalSales',
            'chartLabels',
            'chartGMV',
            'chartItems',
            'topCampaigns',
            'campaignLabels',
            'campaignGMV',
            'topUploadUsers',
            'userNames',
            'userFiles'
        ));
    }
}
