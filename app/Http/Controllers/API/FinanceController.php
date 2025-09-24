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
        $payment = DB::table('payments')->where('id', $paymentId)->first();
        if (! $payment) {
            abort(404, 'Payment not found.');
        }

        $invoice = DB::table('invoices')->where('id', $payment->invoice_id)->first();
        if (! $invoice) {
            abort(404, 'Invoice not found.');
        }

        if (! Gate::allows('isRole', 'keuangan')) {
            $studentId = DB::table('students')
                ->where('user_id', auth()->id())
                ->value('id');

            if ((int) $invoice->student_id !== (int) $studentId) {
                abort(403);
            }
        }

        // mark payment verified
        DB::table('payments')
            ->where('id', $paymentId)
            ->update(['status' => 'verified', 'updated_at' => now()]);

        // refresh payment after status change to keep response consistent
        $payment = DB::table('payments')->where('id', $paymentId)->first();

        // idempotent posting: use payment_id as posting id
        $postingId = 'payment_' . $paymentId;
        $ledgerId = 1; // default ledger for demo
        $posted = $posting->post($postingId, $ledgerId, (float) $payment->amount, 'credit');

        event(new PaymentVerified((int) $paymentId));

        return response()->json(['verified' => true, 'posted' => $posted]);
    }
}
