<?php

namespace App\Domain\Finance\Services;

use Illuminate\Support\Facades\DB;

class LedgerPostingService
{
    /**
     * Post ledger entry idempotently using posting_id
     */
    public function post(string $postingId, int $ledgerId, float $amount, string $type): bool
    {
        $exists = DB::table('ledger_entries')->where('posting_id',$postingId)->exists();
        if ($exists) return false;

        // Ledger rows must exist via seeder/migrations; do not auto-create here to keep semantics strict
        $ledgerExists = DB::table('ledgers')->where('id', $ledgerId)->exists();
        if (! $ledgerExists) {
            throw new \RuntimeException("Ledger id {$ledgerId} not found");
        }

        DB::table('ledger_entries')->insert([
            'ledger_id' => $ledgerId,
            'posting_id' => $postingId,
            'amount' => $amount,
            'type' => $type,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return true;
    }
}
