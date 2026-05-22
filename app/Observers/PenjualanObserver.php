<?php

namespace App\Observers;

use App\Models\Penjualan;
use App\Services\Accounting\JournalService;

class PenjualanObserver
{
    public function saved(Penjualan $penjualan)
    {
        $reference = 'PENJ-' . $penjualan->id_penjualan;

        if ($penjualan->total_harga <= 0 || $penjualan->total_item <= 0) {
            $this->removeJournal($reference);
            return;
        }

        $detailCost = $penjualan->details()->with('produk')->get()->reduce(function ($carry, $item) {
            return $carry + (($item->produk->harga_beli ?? 0) * $item->jumlah);
        }, 0);

        $lines = [];

        if ($penjualan->bayar > 0) {
            $lines[] = [
                'account_code' => config('accounting.cash'),
                'debit' => $penjualan->bayar,
                'credit' => 0,
                'description' => 'Cash received for sale',
            ];
        }

        $receivable = $penjualan->total_harga - $penjualan->bayar;
        if ($receivable > 0) {
            $lines[] = [
                'account_code' => config('accounting.accounts_receivable'),
                'debit' => $receivable,
                'credit' => 0,
                'description' => 'Accounts receivable for sale',
            ];
        }

        $lines[] = [
            'account_code' => config('accounting.sales_revenue'),
            'debit' => 0,
            'credit' => $penjualan->total_harga,
            'description' => 'Sales revenue',
        ];

        if ($detailCost > 0) {
            $lines[] = [
                'account_code' => config('accounting.cogs'),
                'debit' => $detailCost,
                'credit' => 0,
                'description' => 'Cost of goods sold',
            ];
            $lines[] = [
                'account_code' => config('accounting.inventory'),
                'debit' => 0,
                'credit' => $detailCost,
                'description' => 'Inventory reduction',
            ];
        }

        (new JournalService())->postJournalEntry(
            $reference,
            'Journal for penjualan #' . $penjualan->id_penjualan,
            $penjualan->id_cabang ?? null,
            $penjualan->id_user ?? null,
            $lines,
            optional($penjualan->created_at)->toDateString() ?? now()->toDateString()
        );
    }

    protected function removeJournal(string $reference): void
    {
        \App\Models\JournalEntry::where('reference_number', $reference)->delete();
    }
}
