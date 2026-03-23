<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAgentKycApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Only enforce for agents
        if ($user->hasRole('agent')) {
            \Illuminate\Support\Facades\Log::info('Agent KYC Check', ['agent' => $user->id, 'kyc_status' => $user->kyc_status, 'status' => $user->status]);
            
            if (!$user->isKycApproved()) {
                \Illuminate\Support\Facades\Log::info('Redirecting to KYC required', ['agent' => $user->id]);
                return redirect()->route('agent.kyc.required');
            }

            if ($user->status === 'pending') {
                \Illuminate\Support\Facades\Log::info('Redirecting to Awaiting Approval', ['agent' => $user->id]);
                return redirect()->route('agent.awaiting-approval');
            }
        }

        return $next($request);
    }
}
