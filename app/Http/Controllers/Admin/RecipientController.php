<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipient;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RecipientController extends Controller
{
    public function index(Request $request)
    {
        $recipients = Recipient::with(['customer.agent'])
            ->latest()
            ->paginate(10);

        return view('admin.recipients.index', compact('recipients'));
    }

    public function show(int $id)
    {
        $recipient = Recipient::with(['customer.agent', 'transactions'])->findOrFail($id);
        return view('admin.recipients.show', compact('recipient'));
    }
}
