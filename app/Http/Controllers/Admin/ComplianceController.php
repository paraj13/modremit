<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ComplianceService;
use Illuminate\Http\Request;

class ComplianceController extends Controller
{
    public function __construct(private ComplianceService $complianceService) {}

    public function index(Request $request)
    {
        $logs = $this->complianceService->all($request->all());
        return view('admin.compliance.index', compact('logs'));
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
