<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ImportController extends Controller
{
    public function index()
    {
        return view('import.index');
    }


    /**
     * PREVIEW IMPORT — hanya 3 kolom (Timestamp / PIN / NIP)
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        try {

            Log::info("=== PREVIEW START ===");

            $file = $request->file('file');

            /**
             * FIX TERPENTING !!!
             * Jangan pakai mode XLS, karena menyebabkan sheet kacau.
             */
            $data = Excel::toArray([], $file);  // AUTO detect file type
            $rows = $data[0];

            DB::table('TTK_TRANST')->truncate();

            $preview = [];
            $i = 0;

            foreach ($rows as $index => $row) {

                // Skip header
                if ($index == 0) continue;

                // Pastikan kolom 0,3,4 tersedia
                if (
                    !isset($row[0]) ||
                    !isset($row[3]) ||
                    !isset($row[4]) ||
                    trim($row[0]) === "" ||
                    trim($row[3]) === "" ||
                    trim($row[4]) === ""
                ) {
                    continue;
                }

                // Ambil data 3 kolom
                $timestamp = $row[0];
                $pin       = trim($row[3]);
                $nip       = trim($row[4]);

                // Convert Excel date
                if (is_numeric($timestamp)) {
                    $timestamp = ExcelDate::excelToDateTimeObject($timestamp)->format('Y-m-d H:i:s');
                } else {
                    $timestamp = date('Y-m-d H:i:s', strtotime($timestamp));
                }

                // Masukkan ke preview (max 10 baris)
                if ($i < 10) {
                    $preview[] = [$timestamp, $pin, $nip];
                }

                // Insert ke TTK_TRANST
                DB::table('TTK_TRANST')->insert([
                    'TRNS_DATE'  => $timestamp,
                    'CARD_NMBR'  => $pin,
                    'EMPL_NMBR'  => $nip,
                    'TYPE_CODE'  => '1',
                    'GUNT_PART'  => '1',
                    'OFFI_CODE'  => 'HO',
                    'TERM_NMBR'  => 1,
                    'TRAN_PART'  => '',
                    'TRAN_USR1' => '',
                    'BUSS_CODE'  => 'OE',
                ]);

                $i++;
            }

            Log::info("=== PREVIEW SUCCESS ===");

            return response()->json([
                'preview' => array_merge([
                    ['Timestamp', 'PIN', 'NIP']
                ], $preview)
            ]);

        } catch (\Exception $e) {

            Log::error("=== PREVIEW ERROR ===");
            Log::error($e->getMessage());

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * PROCESS IMPORT → Pindahkan dari TTK ke TK
     */
    public function process()
    {
        DB::beginTransaction();

        try {

            DB::statement("
                INSERT INTO TK_TRANST
                (TRNS_DATE, CARD_NMBR, EMPL_NMBR, TYPE_CODE,
                 GUNT_PART, OFFI_CODE, TERM_NMBR,
                 TRAN_PART, TRAN_USR1, BUSS_CODE)
                SELECT A.TRNS_DATE, A.CARD_NMBR, A.EMPL_NMBR, A.TYPE_CODE,
                       A.GUNT_PART, A.OFFI_CODE, A.TERM_NMBR,
                       A.TRAN_PART, A.TRAN_USR1, A.BUSS_CODE
                FROM TTK_TRANST A
                LEFT JOIN TK_TRANST B
                       ON A.TRNS_DATE = B.TRNS_DATE
                      AND A.EMPL_NMBR = B.EMPL_NMBR
                WHERE B.EMPL_NMBR IS NULL
            ");

            DB::table('TTK_TRANST')->truncate();

            DB::commit();

            return response()->json(['message' => 'Import berhasil diproses!']);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}