<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\ComprehensiveStockReportDetail;
use App\Models\ComprehensiveStockReport;

use App\Models\Product;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Str;

class ComprehensiveStockReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $warehouse_id = $user->warehouse_id;

        $comprehensiveStockReports = ComprehensiveStockReport::with('comprehensiveStockReportDetails')->where('warehouse_id', $warehouse_id)->get();

        info($comprehensiveStockReports);

        return view('employee.comprehensive-stock-report.index', compact('comprehensiveStockReports'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $warehouse_id = $user->warehouse_id;
        $user_id = $user->id;

        $currentDate = now()->endOfDay()->toDateTimeString();

        $lastReport = ComprehensiveStockReport::where('warehouse_id', $warehouse_id)
            // ->where('end_date', '<', $currentDate)
            // ->orderBy('end_date', 'desc')
            ->latest('id')->first();

        if ($lastReport) {
            $end_date = new DateTime($lastReport->end_date);
            $start_date = $end_date->add(new DateInterval('P1D'));
        } else {
            $start_date = now()->startOfYear()->toDateTimeString();
        }

        $end_date = $currentDate;



        $products = Product::select(
            'products.id as product_id',
            'products.name as product_name',
            'products.code as product_code',
            'units.name as unit_name'
        )
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->join('batches', 'products.id', '=', 'batches.product_id')
            ->join('inventories', 'batches.id', '=', 'inventories.batch_id')
            ->leftJoin('goods_receipt_details', function ($join) use ($start_date, $end_date, $warehouse_id) {
                $join->on('products.id', '=', 'goods_receipt_details.product_id')
                    ->join('goods_receipts', 'goods_receipt_details.goods_receipt_id', '=', 'goods_receipts.id')
                    ->where('goods_receipts.warehouse_id', '=', $warehouse_id)
                    ->whereBetween('goods_receipt_details.created_at', [$start_date, $end_date]);
            })
            ->leftJoin('goods_issue_batches', function ($join) use ($start_date, $end_date, $warehouse_id) {
                $join->on('batches.id', '=', 'goods_issue_batches.batch_id')
                    ->where('goods_issue_batches.warehouse_id', '=', $warehouse_id)
                    ->whereBetween('goods_issue_batches.created_at', [$start_date, $end_date]);
            })
            ->where('inventories.warehouse_id', $warehouse_id)
            ->selectRaw('
            0 as beginning_inventory, -- Initialize beginning inventory to 0 for all products
            SUM(DISTINCT inventories.quantity_available) as ending_inventory, -- Use DISTINCT to avoid double-counting inventory
            COALESCE(SUM(DISTINCT goods_receipt_details.quantity), 0) as total_received, -- Use DISTINCT to avoid double-counting receipts
            COALESCE(SUM(DISTINCT goods_issue_batches.quantity), 0) as total_issued -- Use DISTINCT to avoid double-counting issues
        ')
            ->groupBy('products.id', 'products.name', 'products.code', 'units.name')
            ->get();
        info($products);
        $start_date_formatted = \Carbon\Carbon::parse($start_date)->format('Y-m-d');
        $end_date_formatted = \Carbon\Carbon::parse($end_date)->format('Y-m-d');
        return view(
            'employee.comprehensive-stock-report.create',
            compact(
                'warehouse_id',
                'user_id',
                'products',
                'start_date_formatted',
                'end_date_formatted'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        $lastestCode = ComprehensiveStockReport::latest('id')->first();
        if ($lastestCode) {
            $lastNumber = (int)substr($lastestCode->code, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newCode = 'BC' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        $comprehensiveStockReport = ComprehensiveStockReport::create([
            'code' => $newCode,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'warehouse_id' => $request->input('warehouse_id'),
            'user_id' => $request->input('user_id'),
        ]);

        foreach ($request->input('products') as $product) {
            ComprehensiveStockReportDetail::create([
                'comprehensive_stock_report_id' => $comprehensiveStockReport->id,
                'product_id' => $product['product_id'],
                'beginning_inventory' => $product['beginning_inventory'],
                'stock_in' => $product['stock_in'],
                'stock_out' => $product['stock_out'],
                'ending_inventory' => $product['ending_inventory'],
            ]);
        }

        DB::commit();

        return redirect()->route('comprehensive-stock-report.index')->with(['message' => 'Báo cáo được lưu thành công!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
