<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComprehensiveStockReport;
use Illuminate\Http\Request;

class AdminComprehensiveStockReportController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        // $warehouse_id = $user->warehouse_id;

        $comprehensiveStockReports = ComprehensiveStockReport::with('comprehensiveStockReportDetails')->get();

        info($comprehensiveStockReports);

        return view('admin.comprehensive-stock-report.index', compact('comprehensiveStockReports'));
    }
}
