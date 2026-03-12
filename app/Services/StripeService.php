<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Log;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function createCheckoutSession(int $agentId, float $amount)
    {
        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'chf',
                    'product_data' => [
                        'name' => 'Agent Wallet Top-Up',
                    ],
                    'unit_amount' => $amount * 100, // Stripe uses cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('agent.wallet.index') . '?status=success&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('agent.wallet.index') . '?status=cancel',
            'metadata' => [
                'agent_id' => $agentId,
                'amount'   => $amount,
            ],
        ]);
    }

    public function retrieveSession(string $sessionId)
    {
        return Session::retrieve($sessionId);
    }

    public function handleWebhook(string $payload, string $sigHeader)
    {
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe Webhook Error: Invalid Payload');
            return false;
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe Webhook Error: Invalid Signature');
            return false;
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $this->processSuccessfulPayment($session);
        }

        return true;
    }

    public function processSuccessfulPayment($session)
    {
        Log::info('Processing successful Stripe payment', ['session_id' => $session->id]);

        $agentId = $session->metadata['agent_id'] ?? null;
        $amount  = (float) ($session->metadata['amount'] ?? 0);
        $sessionId = $session->id;

        if (!$agentId || $amount <= 0) {
            Log::error('Stripe Payment Error: Missing metadata', ['session_id' => $sessionId]);
            return;
        }

        // Prevent duplicate processing
        if (WalletTransaction::where('stripe_session_id', $sessionId)->exists()) {
            Log::info('Stripe Payment: Session already processed', ['session_id' => $sessionId]);
            return;
        }

        $walletService = app(WalletService::class);
        $walletService->deposit(
            agentId: $agentId,
            amount: $amount,
            description: 'Stripe Wallet Top-Up',
            adminId: null,
            paymentMethod: 'stripe',
            stripeSessionId: $sessionId
        );

        Log::info('Stripe Wallet Top-Up Successful', ['agent_id' => $agentId, 'amount' => $amount]);
    }
}
