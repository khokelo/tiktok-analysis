<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TiktokSale;
use Illuminate\Http\Request;

class SalesManagementController extends Controller
{
    /**
     * Display all sales records
     */
    public function index()
    {
        $sales = TiktokSale::orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->paginate(50);

        $title = 'Sales Management';
        $subtitle = 'Kelola data penjualan TikTok';

        return view('admin.sales.index', compact('sales', 'title', 'subtitle'));
    }

    /**
     * Show form for creating new sale record
     */
    public function create()
    {
        $title = 'Tambah Penjualan Baru';
        $subtitle = 'Masukkan data penjualan baru';

        return view('admin.sales.create', compact('title', 'subtitle'));
    }

    /**
     * Store a newly created sale record
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'campaign' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'direct_gmv' => 'required|numeric|min:0',
            'items_sold' => 'required|integer|min:0',
            'customers' => 'required|integer|min:0',
            'viewers' => 'required|integer|min:0',
        ]);

        TiktokSale::create($validated);

        return redirect()->route('admin.sales.index')
            ->with('success', 'Data penjualan berhasil ditambahkan!');
    }

    /**
     * Show single sale record
     */
    public function show(TiktokSale $sale)
    {
        $title = 'Detail Penjualan';
        $subtitle = 'Lihat detail penjualan';

        return view('admin.sales.show', compact('sale', 'title', 'subtitle'));
    }

    /**
     * Show form for editing sale record
     */
    public function edit(TiktokSale $sale)
    {
        $title = 'Edit Penjualan';
        $subtitle = 'Edit data penjualan';

        return view('admin.sales.edit', compact('sale', 'title', 'subtitle'));
    }

    /**
     * Update the specified sale record
     */
    public function update(Request $request, TiktokSale $sale)
    {
        $validated = $request->validate([
            'campaign' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'direct_gmv' => 'required|numeric|min:0',
            'items_sold' => 'required|integer|min:0',
            'customers' => 'required|integer|min:0',
            'viewers' => 'required|integer|min:0',
        ]);

        $sale->update($validated);

        return redirect()->route('admin.sales.show', $sale)
            ->with('success', 'Data penjualan berhasil diperbarui!');
    }

    /**
     * Delete the specified sale record
     */
    public function destroy(TiktokSale $sale)
    {
        $sale->delete();

        return redirect()->route('admin.sales.index')
            ->with('success', 'Data penjualan berhasil dihapus!');
    }

    /**
     * Bulk delete sale records
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (! empty($ids)) {
            TiktokSale::whereIn('id', $ids)->delete();

            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus!']);
        }

        return response()->json(['success' => false, 'message' => 'Pilih data terlebih dahulu!']);
    }
}
