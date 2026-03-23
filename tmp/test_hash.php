<?php

use App\Models\Transaction;
use Illuminate\Support\Str;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$transaction = Transaction::first();
if ($transaction) {
    if (!$transaction->unique_hash) {
        $transaction->unique_hash = Str::uuid()->toString();
        $transaction->save();
        echo "Updated transaction #{$transaction->id} with hash: {$transaction->unique_hash}\n";
    } else {
        echo "Transaction #{$transaction->id} already has hash: {$transaction->unique_hash}\n";
    }
} else {
    echo "No transactions found.\n";
}
