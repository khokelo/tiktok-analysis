<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TiktokSale;
use App\Models\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileManagementController extends Controller
{
    /**
     * Display list of all uploaded files
     */
    public function index()
    {
        $files = UploadedFile::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.files.index', compact('files'));
    }

    /**
     * Show file details
     */
    public function show(UploadedFile $file)
    {
        $file->load('user');
        $relatedSales = TiktokSale::orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('admin.files.show', compact('file', 'relatedSales'));
    }

    /**
     * Update file status
     */
    public function update(Request $request, UploadedFile $file)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processed,failed',
        ]);

        $file->update($validated);

        return redirect()->route('admin.files.index')
            ->with('success', 'Status file berhasil diperbarui.');
    }

    /**
     * Delete file
     */
    public function destroy(UploadedFile $file)
    {
        try {
            // Delete physical file
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            // Delete record
            $file->delete();

            return redirect()->route('admin.files.index')
                ->with('success', 'File berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.files.index')
                ->with('error', 'Gagal menghapus file: '.$e->getMessage());
        }
    }

    /**
     * Download file
     */
    public function download(UploadedFile $file)
    {
        if (! Storage::disk('public')->exists($file->file_path)) {
            return redirect()->route('admin.files.index')
                ->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($file->file_path, $file->original_name);
    }

    /**
     * Bulk delete files
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:uploaded_files,id',
        ])['ids'];

        $deleted = 0;
        foreach ($ids as $id) {
            try {
                $file = UploadedFile::find($id);
                if ($file) {
                    if (Storage::disk('public')->exists($file->file_path)) {
                        Storage::disk('public')->delete($file->file_path);
                    }
                    $file->delete();
                    $deleted++;
                }
            } catch (\Exception $e) {
                // Continue with next file
            }
        }

        return redirect()->route('admin.files.index')
            ->with('success', "{$deleted} file berhasil dihapus.");
    }
}
