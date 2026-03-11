<?php

namespace App\Services;

use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class WalletService
{
    public function getForAgent(int $agentId): Wallet
    {
        return Wallet::firstOrCreate(
            ['agent_id' => $agentId],
            ['chf_balance' => 0]
        );
    }

    public function credit(int $agentId, float $amount): Wallet
    {
        $wallet = $this->getForAgent($agentId);
        $wallet->increment('chf_balance', $amount);
        return $wallet->fresh();
    }

    public function debit(int $agentId, float $amount): bool
    {
        $wallet = $this->getForAgent($agentId);
        if ($wallet->chf_balance < $amount) {
            return false;
        }
        $wallet->decrement('chf_balance', $amount);
        return true;
    }
}
