<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Recipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipientController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $recipients = $customer->recipients()->latest()->get();
        return view('customer.recipients.index', compact('recipients'));
    }

    public function create()
    {
        return view('customer.recipients.create');
    }

    public function show($id)
    {
        $customer = Auth::guard('customer')->user();
        $recipient = $customer->recipients()->findOrFail($id);
        return view('customer.recipients.show', compact('recipient'));
    }

    public function edit($id)
    {
        $customer = Auth::guard('customer')->user();
        $recipient = $customer->recipients()->findOrFail($id);
        return view('customer.recipients.edit', compact('recipient'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'nullable|email|max:255',
            'bank_name'      => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'ifsc_code'      => 'nullable|string|max:20',
            'upi_id'         => 'nullable|string|max:100',
            'country'        => 'required|string|max:100',
            'address_line_1' => 'nullable|string|max:255',
            'city'           => 'nullable|string|max:100',
            'postal_code'    => 'nullable|string|max:20',
            'state'          => 'nullable|string|max:100',
            'iban'           => 'nullable|string|max:50',
            'swift_code'     => 'nullable|string|max:20',
        ]);

        $customer = Auth::guard('customer')->user();
        $data['customer_id'] = $customer->id;
        Recipient::create($data);

        return redirect()->route('customer.recipients.index')
            ->with('success', 'Recipient added successfully.');
    }

    public function update(Request $request, $id)
    {
        $customer = Auth::guard('customer')->user();
        $recipient = $customer->recipients()->findOrFail($id);

        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'nullable|email|max:255',
            'bank_name'      => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'ifsc_code'      => 'nullable|string|max:20',
            'upi_id'         => 'nullable|string|max:100',
            'country'        => 'required|string|max:100',
            'address_line_1' => 'nullable|string|max:255',
            'city'           => 'nullable|string|max:100',
            'postal_code'    => 'nullable|string|max:20',
            'state'          => 'nullable|string|max:100',
            'iban'           => 'nullable|string|max:50',
            'swift_code'     => 'nullable|string|max:20',
        ]);

        $recipient->update($data);

        return redirect()->route('customer.recipients.index')
            ->with('success', 'Recipient updated successfully.');
    }
}
