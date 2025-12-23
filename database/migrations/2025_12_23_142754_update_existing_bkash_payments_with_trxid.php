<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\PaymentGateway\BkashService;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all bKash payments without trxID in gateway_response
        $bkashPayments = DB::table('payments')
            ->where('payment_method', 'BKASH')
            ->whereNotNull('transaction_id')
            ->get();

        Log::info('Starting bKash payment trxID update', ['total_payments' => $bkashPayments->count()]);

        $bkashService = app(BkashService::class);
        $updated = 0;
        $failed = 0;

        foreach ($bkashPayments as $payment) {
            try {
                // Query payment using transaction_id (paymentID)
                $result = $bkashService->queryPayment($payment->transaction_id);
                
                if ($result['success'] && isset($result['trxID'])) {
                    // Update gateway_response with full bKash response
                    DB::table('payments')
                        ->where('id', $payment->id)
                        ->update([
                            'gateway_response' => json_encode($result),
                            'updated_at' => now(),
                        ]);
                    $updated++;
                    Log::info('Updated payment with trxID', [
                        'payment_id' => $payment->id,
                        'trxID' => $result['trxID']
                    ]);
                } else {
                    $failed++;
                    Log::warning('Failed to get trxID for payment', [
                        'payment_id' => $payment->id,
                        'transaction_id' => $payment->transaction_id,
                        'result' => $result
                    ]);
                }
            } catch (\Exception $e) {
                $failed++;
                Log::error('Exception updating payment trxID', [
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::info('Completed bKash payment trxID update', [
            'updated' => $updated,
            'failed' => $failed
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this data migration
    }
};
