<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $agent = User::create([
            'name'     => 'Main Agent',
            'email'    => 'agent@modremit.com',
            'password' => Hash::make('password'),
            'is_active'=> true,
        ]);

        $agent->assignRole('agent');

        // Initialize wallet
        Wallet::create([
            'agent_id'    => $agent->id,
            'chf_balance' => 10000.00,
        ]);
    }
}
