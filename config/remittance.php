<?php

return [
    'currency_base'           => env('APP_CURRENCY_BASE', 'CHF'),
    'currency_target'         => env('APP_CURRENCY_TARGET', 'INR'),
    'commission_rate'         => (float) env('APP_COMMISSION_RATE', 2),        // percent
    'large_transfer_threshold'=> (float) env('APP_LARGE_TRANSFER_THRESHOLD', 5000), // CHF
];
