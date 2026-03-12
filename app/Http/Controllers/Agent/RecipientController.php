<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Services\RecipientService;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class RecipientController extends Controller
{
    public function __construct(
        private RecipientService $recipientService,
        private CustomerService $customerService
    ) {}

    public function index()
    {
        $recipients = $this->recipientService->listForAgent(auth()->id());
        return view('agent.recipients.index', compact('recipients'));
    }

    public function create(Request $request)
    {
        $customerId = $request->customer_id;
        $customer = $this->customerService->findOwned($customerId);
        if (!$customer) abort(404);
        return view('agent.recipients.create', compact('customer'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'   => 'required|integer',
            'name'          => 'required|string|max:255',
            'bank_name'     => 'required|string|max:255',
            'account_number'=> 'required|string|max:50',
            'ifsc_code'     => 'nullable|string|max:20',
            'upi_id'        => 'nullable|string|max:100',
            'country'       => 'required|string|max:100',
        ]);

        $this->recipientService->create($data['customer_id'], $data);
        return redirect()->route('agent.customers.show', $data['customer_id'])->with('success', 'Recipient added.');
    }

    public function edit(int $id)
    {
        $recipient = $this->recipientService->find($id);
        if (!$recipient || $recipient->customer->agent_id !== auth()->id()) abort(404);
        return view('agent.recipients.edit', compact('recipient'));
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'bank_name'     => 'required|string|max:255',
            'account_number'=> 'required|string|max:50',
            'ifsc_code'     => 'nullable|string|max:20',
            'upi_id'        => 'nullable|string|max:100',
        ]);

        $this->recipientService->update($id, $data);
        $recipient = $this->recipientService->find($id);
        return redirect()->route('agent.customers.show', $recipient->customer_id)->with('success', 'Recipient updated.');
    }
}
