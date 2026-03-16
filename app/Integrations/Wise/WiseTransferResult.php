<?php

namespace App\Integrations\Wise;

/**
 * Value object returned by WiseTransferService::send()
 */
readonly class WiseTransferResult
{
    public function __construct(
        public string $transferId,
        public string $quoteId,
        public string $status,
        public bool   $isDummy,
        public array  $rawResponse,
    ) {}

    public static function dummy(array $data): self
    {
        $fakeId = 'DUMMY-' . strtoupper(uniqid());
        return new self(
            transferId:  $fakeId,
            quoteId:     'DUMMY-Q-' . strtoupper(uniqid()),
            status:      'completed',
            isDummy:     true,
            rawResponse: [
                'note'          => 'Dummy mode — no real Wise API call made',
                'transfer_id'   => $fakeId,
                'target_currency' => $data['target_currency'] ?? 'INR',
            ],
        );
    }
}
