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
        try {
            $customerId = \Illuminate\Support\Facades\Crypt::decryptString($request->eid);
        } catch (\Exception $e) {
            abort(404);
        }
        $customer = $this->customerService->findOwned($customerId);
        if (!$customer) abort(404);
        
        $eid = $request->eid;
        return view('agent.recipients.create', compact('customer', 'eid'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'eid'           => 'required|string',
            'name'          => 'required|string|max:255',
            'bank_name'     => 'required|string|max:255',
            'account_number'=> 'required|string|max:50',
            'ifsc_code'     => 'nullable|string|max:20',
            'upi_id'        => 'nullable|string|max:100',
            'country'       => 'required|string|max:100',
        ]);

        try {
            $customerId = \Illuminate\Support\Facades\Crypt::decryptString($data['eid']);
        } catch (\Exception $e) {
            abort(400, 'Invalid token');
        }

        $data['customer_id'] = $customerId;
        $this->recipientService->create($customerId, $data);
        
        if ($request->return_to === 'transfer') {
            return redirect()->route('agent.transfers.create', ['customer_id' => $customerId])
                ->with('success', 'Beneficiary added successfully.');
        }

        return redirect()->route('agent.customers.show', $customerId)->with('success', 'Recipient added.');
    }

    public function edit(int $id)
    {
        $recipient = $this->recipientService->find($id);
        if (!$recipient || $recipient->customer->agent_id !== auth()->id()) abort(404);
        // Pass both recipient and customer so the unified form has all context
        $customer = $recipient->customer;
        return view('agent.recipients.edit', compact('recipient', 'customer'));
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
