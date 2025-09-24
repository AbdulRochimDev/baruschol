<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Domain\Finance\Events\PaymentVerified;
use App\Domain\Finance\Services\LedgerPostingService;

class FinanceController extends Controller
{
    public function verify($paymentId, Request $req, LedgerPostingService $posting)
    {
        // allow 'keuangan' role or the student who owns the invoice to verify
        $payment = DB::table('payments')->where('id',$paymentId)->first();
        $invoice = DB::table('invoices')->where('id', $payment->invoice_id)->first();

        try {
            Gate::authorize('isRole', 'keuangan');
        } catch (\Throwable $e) {
            // if not keuangan, ensure current user is owner of invoice
            if (auth()->id() !== (int) $invoice->student_id) {
                abort(403);
            }
        }

        // mark payment verified
        DB::table('payments')->where('id',$paymentId)->update(['status'=>'verified','updated_at'=>now()]);

        // idempotent posting: use payment_id as posting id
    // refresh
    $payment = DB::table('payments')->where('id',$paymentId)->first();
    $invoice = DB::table('invoices')->where('id',$payment->invoice_id)->first();

        $postingId = 'payment_'.$paymentId;
        $ledgerId = 1; // default ledger for demo
        $posted = $posting->post($postingId, $ledgerId, (float)$payment->amount, 'credit');

        event(new PaymentVerified($paymentId));

        return response()->json(['verified' => true, 'posted' => $posted]);
    }
}
