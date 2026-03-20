<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function __construct(private \App\Services\CustomerService $customerService) {}

    public function index(Request $request)
    {
        $customers = \App\Models\Customer::with(['agent'])
            ->withCount('recipients')
            ->latest()
            ->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(int $id)
    {
        $customer = \App\Models\Customer::with(['agent', 'recipients', 'transactions'])->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(int $id)
    {
        $customer = \App\Models\Customer::findOrFail($id);
        $agents = \App\Models\User::role('agent')->get();
        return view('admin.customers.edit', compact('customer', 'agents'));
    }

    public function update(Request $request, int $id)
    {
        $customer = \App\Models\Customer::findOrFail($id);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:customers,email,' . $id,
            'phone'    => 'required|string|max:20',
            'agent_id' => 'nullable|exists:users,id',
        ]);

        $customer->update($data);

        return redirect()->route('admin.customers.show', $id)->with('success', 'Customer updated successfully.');
    }

    public function refreshKyc(int $id)
    {
        $status = $this->customerService->refreshKycStatus($id);
        return back()->with('success', 'KYC Status: ' . ucfirst($status ?? 'pending'));
    }

    public function destroy(int $id)
    {
        $customer = \App\Models\Customer::findOrFail($id);
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    }
}
