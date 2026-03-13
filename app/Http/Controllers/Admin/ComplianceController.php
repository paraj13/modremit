<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ComplianceService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ComplianceController extends Controller
{
    public function __construct(
        private ComplianceService $complianceService,
        private \App\Services\TransactionService $transactionService
    ) {}

    public function index(Request $request)
    {
        $logs = \App\Models\ComplianceLog::with(['transaction.agent', 'transaction.customer'])
            ->latest()
            ->paginate(10);

        return view('admin.compliance.index', compact('logs'));
    }

    public function show(int $id)
    {
        $log = $this->complianceService->findById($id);
        return view('admin.compliance.show', compact('log'));
    }

    public function review(Request $request, int $id)
    {
        $log = $this->complianceService->findById($id);
        if (!$log) abort(404);

        $request->validate([
            'notes' => 'nullable|string',
            'action' => 'required|in:review',
        ]);
        
        // 1. Mark log as reviewed
        $this->complianceService->reviewLog($id, $request->notes);

        return redirect()->route('admin.compliance.index')->with('success', 'Compliance review recorded.');
    }
}
