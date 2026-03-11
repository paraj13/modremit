<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ComplianceService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ComplianceController extends Controller
{
    public function __construct(private ComplianceService $complianceService) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = \App\Models\ComplianceLog::with(['transaction.agent', 'transaction.customer'])->select('compliance_logs.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('agent', function($row){
                    return $row->transaction->agent->name ?? 'N/A';
                })
                ->addColumn('customer', function($row){
                    return $row->transaction->customer->name ?? 'N/A';
                })
                ->addColumn('amount', function($row){
                    return 'CHF ' . number_format($row->transaction->chf_amount, 2);
                })
                ->editColumn('status', function($row){
                    $class = match($row->status) {
                        'pending'   => 'bg-warning text-dark',
                        'reviewed'  => 'bg-info',
                        'cleared'   => 'bg-success',
                        'escalated' => 'bg-danger',
                        default     => 'bg-secondary',
                    };
                    return '<span class="badge '.$class.'">'.ucfirst($row->status).'</span>';
                })
                ->addColumn('action', function($row){
                    return '<a href="'.route('admin.compliance.show', $row->id).'" class="btn btn-sm btn-outline-primary">Review</a>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.compliance.index');
    }

    public function show(int $id)
    {
        $log = $this->complianceService->findById($id);
        return view('admin.compliance.show', compact('log'));
    }

    public function review(Request $request, int $id)
    {
        $request->validate(['notes' => 'nullable|string']);
        $this->complianceService->reviewLog($id, $request->notes);
        return redirect()->route('admin.compliance.index')->with('success', 'Compliance log reviewed.');
    }
}
