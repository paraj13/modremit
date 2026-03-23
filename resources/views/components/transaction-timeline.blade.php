@props(['transaction'])

<div class="transaction-timeline-wrapper">
    <div class="timeline-stepper">
        @php
            $steps = [
                [
                    'id' => 'initiated',
                    'title' => 'Transaction Initiated',
                    'desc' => 'Your transfer request has been successfully created.',
                    'icon' => 'bi-file-earmark-plus',
                    'time' => $transaction->created_at->format('M d, H:i'),
                ],
                [
                    'id' => 'payment_received',
                    'title' => 'Payment Received',
                    'desc' => 'Funds have been collected and verified.',
                    'icon' => 'bi-wallet2',
                    'time' => $transaction->created_at->addMinutes(2)->format('M d, H:i'),
                ],
                [
                    'id' => 'processing',
                    'title' => 'Processing Transfer',
                    'desc' => 'The transfer is now in our processing queue.',
                    'icon' => 'bi-gear-wide-connected',
                    'time' => $transaction->created_at->addMinutes(5)->format('M d, H:i'),
                ],
                [
                    'id' => 'converted',
                    'title' => 'Currency Converted',
                    'desc' => 'CHF has been converted to ' . $transaction->target_currency . '.',
                    'icon' => 'bi-currency-exchange',
                    'time' => $transaction->created_at->addMinutes(10)->format('M d, H:i'),
                ],
                [
                    'id' => 'sent',
                    'title' => 'Transfer Sent',
                    'desc' => 'Money has been dispatched to the recipient bank.',
                    'icon' => 'bi-send-check',
                    'time' => $transaction->created_at->addMinutes(15)->format('M d, H:i'),
                ],
                [
                    'id' => 'completed',
                    'title' => 'Completed',
                    'desc' => 'Funds reached the recipient account.',
                    'icon' => 'bi-check2-all',
                    'time' => $transaction->status === 'completed'
                        ? $transaction->updated_at->format('M d, H:i')
                        : null,
                ]
            ];

            // 🔄 Reverse order (latest on top)
            $steps = array_reverse($steps);
        @endphp

        @foreach($steps as $index => $step)
            @php
                $isLast = $loop->last;

                // Static completed UI (as per requirement)
                $iconBg = 'bg-brand-mint';
                $iconColor = 'text-brand-dark';
                $titleClass = 'text-brand-dark';
            @endphp

            <div class="timeline-item d-flex mb-0">
                <div class="timeline-indicator d-flex flex-column align-items-center me-3">
                    
                    <!-- ICON -->
                    <div class="timeline-icon rounded-circle d-flex align-items-center justify-content-center shadow-sm {{ $iconBg }} {{ $iconColor }} icon-timeline-step">
                        <i class="bi {{ $step['icon'] }} fs-5"></i>
                    </div>

                    <!-- DOTTED LINE -->
                    @if(!$isLast)
                        <div class="timeline-line"></div>
                    @endif

                </div>

                <div class="timeline-content pb-4 flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <h6 class="fw-bold mb-1 {{ $titleClass }}">
                            {{ $step['title'] }}
                        </h6>

                        @if($step['time'])
                            <span class="text-muted user-info-text">
                                {{ $step['time'] }}
                            </span>
                        @endif
                    </div>

                    <p class="text-muted small mb-0">
                        {{ $step['desc'] }}
                    </p>

                    @if($index === count($steps) - 1 && $transaction->initiated_by)
                        <span class="badge bg-light text-dark border mt-2 px-2 py-1 user-kyc-badge">
                            Via: {{ ucfirst($transaction->initiated_by) }}
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .timeline-item:last-child .timeline-content {
        padding-bottom: 0 !important;
    }

    /* 🔵 Dotted vertical line */
    .timeline-line {
        width: 2px;
        flex-grow: 1;
        margin: 6px 0;
        border-left: 2px dotted var(--brand-dark); /* your yellow theme */
        min-height: 50px;
        opacity: 0.7;
    }

    .table-premium-container .timeline-icon {
        border: 2px solid white;
    }
</style>