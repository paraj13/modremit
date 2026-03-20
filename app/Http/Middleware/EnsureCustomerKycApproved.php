<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureCustomerKycApproved
{
    public function handle(Request $request, Closure $next)
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect()->route('customer.login');
        }

        if (!$customer->isKycApproved()) {
            return redirect()->route('customer.kyc.required');
        }

        return $next($request);
    }
}
