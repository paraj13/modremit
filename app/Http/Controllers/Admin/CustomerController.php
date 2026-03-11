<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::with(['agent'])
            ->withCount('recipients')
            ->latest()
            ->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(int $id)
    {
        $customer = Customer::with(['agent', 'recipients', 'transactions'])->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }
}
