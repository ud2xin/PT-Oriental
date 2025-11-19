<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AttendanceImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ImportController extends Controller
{
    public function index()
    {
        // Bersihkan session jika ada sisa file sebelumnya
        session()->forget(['import_file_path', 'import_file_type']); // <-- Update ini
        return view('import.index');
    }

    /**
     * Metode ini tampaknya tidak digunakan oleh alur AJAX Anda,
     * tetapi saya biarkan jika Anda membutuhkannya untuk hal lain.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        try {
            Excel::import(new AttendanceImport, $request->file('file'));

            return redirect()->route('attendance.index')
                ->with('success', 'Data absensi berhasil diimport!');
        } catch (\Exception $e) {

            Log::error('Error saat import absensi: '.$e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Import gagal! Cek log untuk detail error.');
        }
    }

    /**
     * Menampilkan pratinjau data dari file Excel.
     * File akan disimpan sementara di storage.
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv' // Izinkan CSV
        ]);

        try {
            $file = $request->file('file');
            
            // 1. Tentukan Tipe File sebagai String Sederhana
            $extension = strtolower($file->getClientOriginalExtension());
            $fileType = null; // Tipe Reader (String)

            if ($extension == 'csv') {
                $fileType = 'Csv';
            } elseif ($extension == 'xls') {
                $fileType = 'Xls';
            } elseif ($extension == 'xlsx') {
                $fileType = 'Xlsx';
            }
            
            // Jika ekstensinya .xls tapi aslinya CSV (seperti kasus Anda)
            // Kita prioritaskan CSV jika ekstensinya .xls
            // Ini adalah asumsi berdasarkan file Anda '15-21.xls'
            if ($extension == 'xls' && $file->getMimeType() == 'text/csv') {
                 $fileType = 'Csv';
            }
            
            // 2. Simpan file sementara
            $path = $file->store('imports');
            
            // 3. Simpan path DAN TIPE FILE (String) di session
            session([
                'import_file_path' => $path,
                'import_file_type' => $fileType 
            ]);

            // 4. Baca data untuk pratinjau, beritahu tipenya (argumen ke-4)
            $data = Excel::toArray(new \stdClass, $path, null, $fileType);
            
            $previewData = array_slice($data[0], 0, 10); 

            // 5. Kembalikan data pratinjau sebagai JSON
            return response()->json([
                'preview' => $previewData
            ]);

        } catch (\Exception $e) {
            Log::error('Error saat preview absensi: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            session()->forget(['import_file_path', 'import_file_type']);
            return response()->json(['error' => 'Gagal memuat preview. Cek log server.'], 500);
        }
    }

    /**
     * Memproses file impor yang disimpan di session.
     */
    public function process(Request $request)
    {
        $filePath = session('import_file_path');
        $fileType = session('import_file_type');

        if (!$filePath || !Storage::disk('local')->exists($filePath)) {
            return response()->json(['error' => 'File impor tidak ditemukan. Silakan unggah ulang.'], 400);
        }

        // --- TAMBAHKAN BARIS INI ---
        // Tentukan importer yang akan digunakan (sesuai file AttendanceImport.php)
        $importer = new AttendanceImport();
        // ---------------------------
        
        try {
            Log::info("Starting import for file: $filePath");
            
            // Sekarang variabel $importer sudah ada
            // (Gunakan 'MaatExcel' jika Anda me-rename 'Excel' di 'use' statement)
            Excel::import($importer, $filePath, 'local'); 

            Storage::disk('local')->delete($filePath);
            session()->forget(['import_file_path', 'import_file_type', 'import_preview_data']);

            // Menggunakan $importer->getRowCount() mungkin tidak tersedia di ToModel,
            // jadi kita kirim pesan sukses standar saja.
            return response()->json(['message' => 'Impor berhasil! Data sedang diproses.']);
        
        } catch (\Exception $e) {
            Log::error('Error during import process: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Terjadi kesalahan internal saat impor.'], 500);
        }
    }
}