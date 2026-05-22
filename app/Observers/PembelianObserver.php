<?php

namespace App\Observers;

use App\Models\Pembelian;
use App\Services\Accounting\JournalService;

class PembelianObserver
{
    public function saved(Pembelian $pembelian)
    {
        $reference = 'PB-' . $pembelian->id_pembelian;

        if ($pembelian->total_harga <= 0 || $pembelian->total_item <= 0) {
            $this->removeJournal($reference);
            return;
        }

        $lines = [
            [
                'account_code' => config('accounting.inventory'),
                'debit' => $pembelian->total_harga,
                'credit' => 0,
                'description' => 'Inventory purchase',
            ],
        ];

        if ($pembelian->bayar > 0) {
            $lines[] = [
                'account_code' => config('accounting.cash'),
                'debit' => 0,
                'credit' => $pembelian->bayar,
                'description' => 'Cash payment for purchase',
            ];
        }

        $payable = $pembelian->total_harga - $pembelian->bayar;
        if ($payable > 0) {
            $lines[] = [
                'account_code' => config('accounting.accounts_payable'),
                'debit' => 0,
                'credit' => $payable,
                'description' => 'Accounts payable for purchase',
            ];
        }

        (new JournalService())->postJournalEntry(
            $reference,
            'Journal for pembelian #' . $pembelian->id_pembelian,
            $pembelian->id_cabang ?? null,
            $pembelian->id_user ?? null,
            $lines,
            optional($pembelian->created_at)->toDateString() ?? now()->toDateString()
        );
    }

    protected function removeJournal(string $reference): void
    {
        \App\Models\JournalEntry::where('reference_number', $reference)->delete();
    }
}
