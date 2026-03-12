<?php

namespace App\Services;

use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function getForAgent(int $agentId): Wallet
    {
        return Wallet::firstOrCreate(
            ['agent_id' => $agentId],
            ['chf_balance' => 0]
        );
    }

    public function deposit(int $agentId, float $amount, string $description = 'Admin Credit', ?int $adminId = null, ?string $paymentMethod = null, ?string $stripeSessionId = null): Wallet
    {
        return DB::transaction(function () use ($agentId, $amount, $description, $adminId, $paymentMethod, $stripeSessionId) {
            $wallet = $this->getForAgent($agentId);
            $wallet->increment('chf_balance', $amount);
            $wallet->increment('total_received', $amount);

            $wallet->transactions()->create([
                'type'         => 'deposit',
                'amount'       => $amount,
                'description'  => $description,
                'payment_method' => $paymentMethod,
                'stripe_session_id' => $stripeSessionId,
                'reference_type' => 'User',
                'reference_id'   => $adminId ?? Auth::id(),
                'status'       => 'completed',
                'created_by'   => $adminId ?? Auth::id(),
            ]);

            return $wallet->fresh();
        });
    }

    public function debitForTransfer(int $agentId, float $amount, \App\Models\Transaction $transaction): bool
    {
        return DB::transaction(function () use ($agentId, $amount, $transaction) {
            $wallet = $this->getForAgent($agentId);
            
            if ($wallet->chf_balance < $amount) {
                return false;
            }

            $wallet->decrement('chf_balance', $amount);
            
            $wallet->transactions()->create([
                'type'         => 'transfer',
                'amount'       => -$amount,
                'description'  => 'Transfer to ' . $transaction->recipient->name,
                'reference_type' => 'Transaction',
                'reference_id'   => $transaction->id,
                'status'       => 'completed',
                'created_by'   => $agentId,
            ]);

            return true;
        });
    }

    public function getHistory(int $agentId)
    {
        return $this->getForAgent($agentId)->transactions()->latest()->paginate(20);
    }
}
