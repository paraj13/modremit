<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FxRate;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FxRateController extends Controller
{
    public function index(Request $request)
    {
        $fxRates = FxRate::latest()->paginate(10);
        return view('admin.fx.index', compact('fxRates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_currency' => 'required|string|size:3',
            'to_currency'   => 'required|string|size:3',
            'rate'          => 'required|numeric|min:0',
        ]);

        FxRate::updateOrCreate(
            ['from_currency' => $request->from_currency, 'to_currency' => $request->to_currency],
            ['rate' => $request->rate, 'is_active' => true]
        );

        return response()->json(['success' => 'FX Rate saved successfully.']);
    }

    public function edit($id)
    {
        $fxRate = FxRate::find($id);
        return response()->json($fxRate);
    }

    public function destroy($id)
    {
        FxRate::find($id)->delete();
        return response()->json(['success' => 'FX Rate deleted successfully.']);
    }
}
