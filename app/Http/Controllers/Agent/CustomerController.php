<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function __construct(private CustomerService $customerService) {}

    public function index(Request $request)
    {
        $customers = \App\Models\Customer::where('agent_id', auth()->id())
            ->withCount('recipients')
            ->latest()
            ->paginate(10);

        return view('agent.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('agent.customers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email',
            'phone' => 'required|string|max:20',
        ]);

        print("Creating customer...");
        $this->customerService->create($data);

        return redirect()->route('agent.customers.index')->with('success', 'Customer created and KYC initiated.');
    }

    public function show(int $id)
    {
        $customer = $this->customerService->findOwned($id);
        if (!$customer) abort(404);
        return view('agent.customers.show', compact('customer'));
    }

    public function edit(int $id)
    {
        $customer = $this->customerService->findOwned($id);
        if (!$customer) abort(404);
        return view('agent.customers.edit', compact('customer'));
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $id,
            'phone' => 'required|string|max:20',
        ]);

        $this->customerService->update($id, $data);
        return redirect()->route('agent.customers.index')->with('success', 'Customer updated.');
    }

    public function refreshKyc(int $id)
    {
        $status = $this->customerService->refreshKycStatus($id);
        return back()->with('success', 'KYC Status: ' . ucfirst($status ?? 'pending'));
    }

    public function destroy(int $id)
    {
        $customer = $this->customerService->findOwned($id);
        if (!$customer) abort(404);
        
        $customer->delete();
        return redirect()->route('agent.customers.index')->with('success', 'Customer deleted successfully.');
    }
}
