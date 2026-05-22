<?php

namespace App\Services\Accounting;

use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use Illuminate\Support\Carbon;

class JournalService
{
    public function postJournalEntry(string $referenceNumber, string $description, ?int $branchId, ?int $userId, array $lines, ?string $date = null): JournalEntry
    {
        $date = $date ?: Carbon::today()->toDateString();

        $entry = JournalEntry::firstOrNew(['reference_number' => $referenceNumber]);
        $entry->date = $date;
        $entry->notes = $description;
        $entry->id_cabang = $branchId;
        $entry->id_user = $userId;
        $entry->save();

        $this->syncJournalItems($entry, $lines);

        return $entry;
    }

    protected function syncJournalItems(JournalEntry $entry, array $lines): void
    {
        $entry->items()->delete();

        foreach ($lines as $line) {
            $account = $this->findAccount($line['account_code'] ?? null);
            if (! $account) {
                continue;
            }

            $debit = intval($line['debit'] ?? 0);
            $credit = intval($line['credit'] ?? 0);

            if ($debit === 0 && $credit === 0) {
                continue;
            }

            $entry->items()->create([
                'account_id' => $account->id,
                'debit' => $debit,
                'credit' => $credit,
                'description' => $line['description'] ?? null,
            ]);
        }
    }

    protected function findAccount(?string $code): ?ChartOfAccount
    {
        return ChartOfAccount::where('code', $code)->first();
    }
}
