<?php

namespace App\Http\Controllers;

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
     * PREVIEW:
     * - Baca Excel fingerprint
     * - Ambil: timestamp (col 0), PIN (col 3), NIP (col 4)
     * - Skip header + baris kosong
     * - Insert TTK_TRANST dengan default values
     * - Kembalikan SEMUA baris untuk DataTables
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        try {

            $file = $request->file('file');

            // XLS (machine fingerprint format)
            $data = Excel::toArray([], $file, null, \Maatwebsite\Excel\Excel::XLS);
            $rows = $data[0] ?? [];

            // Reset temp table
            DB::table('TTK_TRANST')->truncate();

            $preview = [];

            foreach ($rows as $index => $row) {

                // MINIMAL Excel punya kolom: 0,3,4
                if (!isset($row[0]) || !isset($row[3]) || !isset($row[4])) {
                    continue;
                }

                // Skip baris header
                if (
                    strtolower(trim($row[0])) === 'timestamp' ||
                    strtolower(trim($row[3])) === 'pin' ||
                    strtolower(trim($row[4])) === 'nip'
                ) {
                    continue;
                }

                // Skip baris kosong
                if ($row[0] === "" || $row[3] === "" || $row[4] === "") {
                    continue;
                }

                // Ambil data
                $timestamp = $row[0];
                $pin = trim($row[3]);
                $nip = trim($row[4]);

                // Convert Excel date
                if (is_numeric($timestamp)) {
                    $timestamp = ExcelDate::excelToDateTimeObject($timestamp)->format('Y-m-d H:i:s');
                } else {
                    $timestamp = date('Y-m-d H:i:s', strtotime($timestamp));
                }

                // Simpan ke preview array (SEMUA BARIS)
                $preview[] = [
                    $timestamp,
                    $pin,
                    $nip
                ];

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
                    'TRAN_USR1'  => '',
                    'BUSS_CODE'  => 'OE'
                ]);
            }

            // Return FULL preview untuk DataTables
            return response()->json([
                'preview' => array_merge([
                    ['Timestamp','PIN','NIP']
                ], $preview)
            ]);

        } catch (\Exception $e) {

            Log::error("PREVIEW ERROR: " . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PROCESS:
     * Pindahkan data dari TTK_TRANST → TK_TRANST (tanpa duplikat)
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
            Log::error("PROCESS ERROR: " . $e->getMessage());

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
