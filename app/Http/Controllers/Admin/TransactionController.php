<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Transaction::with(['agent', 'customer', 'recipient'])->select('transactions.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('agent', function($row){
                    return $row->agent->name ?? 'N/A';
                })
                ->addColumn('customer', function($row){
                    return $row->customer->name ?? 'N/A';
                })
                ->addColumn('recipient', function($row){
                    return $row->recipient->name ?? 'N/A';
                })
                ->addColumn('amount', function($row){
                    return 'CHF ' . number_format($row->chf_amount, 2);
                })
                ->editColumn('status', function($row){
                    $class = match($row->status) {
                        'completed'  => 'bg-success',
                        'processing' => 'bg-info',
                        'failed'     => 'bg-danger',
                        default      => 'bg-warning',
                    };
                    return '<span class="badge '.$class.'">'.ucfirst($row->status).'</span>';
                })
                ->addColumn('action', function($row){
                    return '<a href="javascript:void(0)" class="btn btn-sm btn-outline-primary viewDetails" data-id="'.$row->id.'">View</a>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.transactions.index');
    }
}
