<?php

namespace App\Observers;

use App\Models\Pengeluaran;
use App\Services\Accounting\JournalService;

class PengeluaranObserver
{
    public function saved(Pengeluaran $pengeluaran)
    {
        $reference = 'PENG-' . $pengeluaran->id_pengeluaran;

        if ($pengeluaran->nominal <= 0) {
            $this->removeJournal($reference);
            return;
        }

        $lines = [
            [
                'account_code' => config('accounting.operational_expenses'),
                'debit' => $pengeluaran->nominal,
                'credit' => 0,
                'description' => 'Operational expense',
            ],
            [
                'account_code' => config('accounting.cash'),
                'debit' => 0,
                'credit' => $pengeluaran->nominal,
                'description' => 'Cash payment for expense',
            ],
        ];

        (new JournalService())->postJournalEntry(
            $reference,
            'Journal for pengeluaran #' . $pengeluaran->id_pengeluaran,
            $pengeluaran->id_cabang ?? null,
            $pengeluaran->id_user ?? null,
            $lines,
            optional($pengeluaran->created_at)->toDateString() ?? now()->toDateString()
        );
    }

    protected function removeJournal(string $reference): void
    {
        \App\Models\JournalEntry::where('reference_number', $reference)->delete();
    }
}
