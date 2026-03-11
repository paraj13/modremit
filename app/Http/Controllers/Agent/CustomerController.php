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
        if ($request->ajax()) {
            $data = \App\Models\Customer::where('agent_id', auth()->id())
                ->withCount('recipients')
                ->select('customers.*');
                
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('kyc_status', function($row){
                    $class = match($row->kyc_status) {
                        'approved' => 'bg-success',
                        'rejected' => 'bg-danger',
                        'pending'  => 'bg-warning text-dark',
                        default    => 'bg-secondary'
                    };
                    return '<span class="badge '.$class.' px-3">'.strtoupper($row->kyc_status).'</span>';
                })
                ->addColumn('contact', function($row){
                    return '<div>'.$row->email.'</div><small class="text-muted small">'.$row->phone.'</small>';
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('agent.customers.show', $row->id).'" class="btn btn-sm btn-outline-info me-1">View</a>';
                    $btn .= '<a href="'.route('agent.customers.edit', $row->id).'" class="btn btn-sm btn-outline-primary me-1">Edit</a>';
                    $btn .= '<form action="'.route('agent.customers.refresh-kyc', $row->id).'" method="POST" class="d-inline">
                                '.csrf_field().'
                                <button type="submit" class="btn btn-sm btn-outline-secondary">Refresh</button>
                             </form>';
                    return $btn;
                })
                ->rawColumns(['kyc_status', 'contact', 'action'])
                ->make(true);
        }

        return view('agent.customers.index');
    }

    public function create()
    {
        return view('agent.customers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

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
            'email' => 'required|email|max:255',
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
}
