<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionExportController extends Controller
{
    public function export(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $userId = Auth::id();
        
        $query = Transaction::where('user_id', $userId);
        if ($filter === 'in') {
            $query->where('type', 'INCOME');
        } elseif ($filter === 'out') {
            $query->where('type', 'EXPENSE');
        }
        
        $transactions = $query->latest()->get();
        
        $fileName = 'laporan-keuangan-' . date('Ymd') . '.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['No', 'Tanggal', 'Tipe', 'Kategori', 'Jumlah', 'Catatan'];

        $callback = function() use($transactions, $columns) {
            $file = fopen('php://output', 'w');
            // Add BOM for Excel UTF-8
            fputs($file, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));
            fputcsv($file, $columns);

            foreach ($transactions as $index => $transaction) {
                $type = $transaction->type == 'INCOME' ? 'Pemasukan' : 'Pengeluaran';
                $row['No']  = $index + 1;
                $row['Tanggal']    = $transaction->created_at->format('d/m/Y H:i');
                $row['Tipe']    = $type;
                $row['Kategori']  = $transaction->category ?? '-';
                $row['Jumlah']  = $transaction->amount;
                $row['Catatan']  = $transaction->notes ?? '-';

                fputcsv($file, array($row['No'], $row['Tanggal'], $row['Tipe'], $row['Kategori'], $row['Jumlah'], $row['Catatan']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
