<?php

namespace App\Exceptions;

use Exception;

class InsufficientWalletBalanceException extends Exception
{
    protected $message = 'Insufficient wallet balance. Please top up your wallet.';
}
