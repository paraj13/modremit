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
        if ($request->ajax()) {
            $data = Recipient::with(['customer.agent'])->select('recipients.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer', function($row){
                    return $row->customer->name ?? 'N/A';
                })
                ->addColumn('agent', function($row){
                    return $row->customer->agent->name ?? 'N/A';
                })
                ->addColumn('bank_details', function($row){
                    if($row->upi_id) return 'UPI: ' . $row->upi_id;
                    return $row->bank_name . ' (' . $row->account_number . ')';
                })
                ->addColumn('action', function($row){
                    return '<a href="'.route('admin.recipients.show', $row->id).'" class="btn btn-sm btn-outline-primary">View</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.recipients.index');
    }

    public function show(int $id)
    {
        $recipient = Recipient::with(['customer.agent', 'transactions'])->findOrFail($id);
        return view('admin.recipients.show', compact('recipient'));
    }
}
