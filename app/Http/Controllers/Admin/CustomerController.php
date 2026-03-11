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
        if ($request->ajax()) {
            $data = Customer::with(['agent'])->withCount('recipients')->select('customers.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('agent', function($row){
                    return $row->agent->name ?? 'N/A';
                })
                ->editColumn('kyc_status', function($row){
                    $class = match($row->kyc_status) {
                        'approved' => 'bg-success',
                        'rejected' => 'bg-danger',
                        'pending'  => 'bg-warning text-dark',
                        default    => 'bg-secondary'
                    };
                    return '<span class="badge '.$class.'">'.ucfirst($row->kyc_status).'</span>';
                })
                ->addColumn('action', function($row){
                    return '<a href="'.route('admin.customers.show', $row->id).'" class="btn btn-sm btn-outline-primary">View</a>';
                })
                ->rawColumns(['kyc_status', 'action'])
                ->make(true);
        }

        return view('admin.customers.index');
    }

    public function show(int $id)
    {
        $customer = Customer::with(['agent', 'recipients', 'transactions'])->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }
}
