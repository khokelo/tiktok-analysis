<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TiktokSale;
use App\Models\UploadedFile;
use App\Models\User;

class AdminDashboardController extends Controller
{
    /**
     * Display admin dashboard with statistics and charts
     */
    public function index()
    {
        // Total statistics
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalRegularUsers = User::where('role', 'user')->count();
        $totalFiles = UploadedFile::count();
        $totalSales = TiktokSale::count();

        // Files statistics
        $filesUploaded = UploadedFile::count();
        $filesProcessed = UploadedFile::where('status', 'processed')->count();
        $filesFailed = UploadedFile::where('status', 'failed')->count();
        $totalFileSize = UploadedFile::sum('file_size');

        // Recent files
        $recentFiles = UploadedFile::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Sales data for charts
        $salesByDate = TiktokSale::selectRaw('DATE(date) as date, SUM(direct_gmv) as total_gmv, SUM(items_sold) as total_items, COUNT(*) as records')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get()
            ->reverse();

        // Top campaigns by GMV
        $topCampaigns = TiktokSale::selectRaw('campaign, SUM(direct_gmv) as total_gmv, COUNT(*) as records, AVG(direct_gmv) as avg_gmv')
            ->groupBy('campaign')
            ->orderByDesc('total_gmv')
            ->limit(10)
            ->get();

        // Files by user
        $filesByUser = UploadedFile::selectRaw('user_id, users.name, COUNT(*) as total_files, SUM(file_size) as total_size')
            ->join('users', 'uploaded_files.user_id', '=', 'users.id')
            ->groupBy('user_id', 'users.name')
            ->orderByDesc('total_files')
            ->get();

        // Users with most uploads
        $topUploadUsers = User::withCount('uploadedFiles')
            ->orderByDesc('uploaded_files_count')
            ->limit(8)
            ->get();

        // Chart data - Daily GMV Trend
        $chartLabels = $salesByDate->pluck('date')->map(function ($date) {
            return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d M');
        })->toArray();
        $chartGMV = $salesByDate->pluck('total_gmv')->toArray();
        $chartItems = $salesByDate->pluck('total_items')->toArray();

        // Campaign performance data
        $campaignLabels = $topCampaigns->pluck('campaign')->toArray();
        $campaignGMV = $topCampaigns->pluck('total_gmv')->toArray();

        // User upload distribution
        $userNames = $filesByUser->pluck('name')->toArray();
        $userFiles = $filesByUser->pluck('total_files')->toArray();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalRegularUsers',
            'totalFiles',
            'totalSales',
            'filesUploaded',
            'filesProcessed',
            'filesFailed',
            'totalFileSize',
            'recentFiles',
            'topCampaigns',
            'filesByUser',
            'topUploadUsers',
            'chartLabels',
            'chartGMV',
            'chartItems',
            'campaignLabels',
            'campaignGMV',
            'userNames',
            'userFiles'
        ));
    }
}
