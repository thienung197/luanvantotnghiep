<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Customer;
use App\Models\GoodsIssue;
use App\Models\GoodsIssueBatch;
use App\Models\GoodsIssueDetail;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Provider;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GoodsIssueController extends Controller
{
    protected $goodsIssue;
    protected $goodsIssueDetail;
    protected $goodsIssueBatch;
    protected $batch;
    protected $warehouse;
    protected $customer;
    protected $inventory;
    protected $user;
    public function __construct(
        GoodsIssue $goodsIssue,
        Warehouse $warehouse,
        Customer $customer,
        GoodsIssueDetail $goodsIssueDetail,
        Batch $batch,
        Inventory $inventory,
        User $user,
        GoodsIssueBatch $goodsIssueBatch
    ) {
        $this->goodsIssue = $goodsIssue;
        $this->goodsIssueDetail = $goodsIssueDetail;
        $this->batch = $batch;
        $this->warehouse = $warehouse;
        $this->customer = $customer;
        $this->inventory = $inventory;
        $this->user = $user;
        $this->goodsIssueBatch = $goodsIssueBatch;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        $goodsIssues = $this->goodsIssue::with('user')->where('customer_id', $userId)->latest('id')->get();
        return view('employee.goods-issues.index', compact('goodsIssues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = $this->warehouse->all();
        // $customers = $this->customer->all();
        $creators = $this->user->all();
        $user = Auth::user();
        $locationId = $user->location ? $user->location->id : null;
        $latestCode = GoodsIssue::orderByDesc('id')->first();
        if ($latestCode) {
            $lastNumber = (int)substr($latestCode->code, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newCode = 'DH' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        $cart = session()->get('cart', []);
        $products = Product::whereIn('id', $cart)->get();
        $customer = auth()->user();
        $customerId = $customer->id;
        return view('employee.goods-issues.create', compact(
            'warehouses',
            // 'customers',
            'creators',
            'locationId',
            'newCode',
            'user',
            'products',
            'customerId'
        ));
    }

    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);
        info($cart);
        if (($key = array_search($productId, $cart)) !== false) {
            unset($cart[$key]);
        }
        session()->put('cart', $cart);
        info($cart);
        return to_route('goodsissues.create')->with('success', "Sản phẩm đươc xóa khỏi giỏ hàng!");
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     // dd($request->all());
    //     DB::beginTransaction();
    //     // Tạo Goods Issue
    //     $goodsIssue = $this->goodsIssue->create([
    //         'code' => $request->code,
    //         'customer_id' => $request->customer_id,
    //         'status' => 'pending',
    //         'approved_by' => null
    //     ]);

    //     foreach ($request->inputs as $input) {
    //         if (isset($input['product_id']) && isset($input['quantity'])) {
    //             // Tạo Goods Issue Detail
    //             $goodsIssueDetail = $this->goodsIssueDetail->create([
    //                 'goods_issue_id' => $goodsIssue->id,
    //                 'product_id' => $input['product_id'],
    //                 'quantity' => $input['quantity'],
    //                 'unit_price' => $input['unit-price'] ?? 0,
    //                 'discount' => $input['discount'] ?? 0,
    //             ]);

    //             // if (isset($input['batches']) && is_array($input['batches'])) {
    //             //     foreach ($input['batches'] as $batch) {
    //             //         $this->goodsIssueBatch->create([
    //             //             'goods_issue_detail_id' => $goodsIssueDetail->id,
    //             //             'batch_id' => $batch['batch_id'],
    //             //             'quantity' => $batch['quantity'],
    //             //             'warehouse_id' => $batch['warehouse_id'],
    //             //         ]);
    //             //     }
    //             // }
    //         }
    //     }
    //     DB::commit();
    //     // Điều hướng về trang goods issues và hiển thị thông báo
    //     return to_route("goodsissues.index")->with("message", "Đơn hàng được đặt thành công!");
    // }

    public function storeOrder(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        $customerId = $request->input('customer_id');
        $cartData = json_decode($request->input('cartData'), true);
        $latestCode = GoodsIssue::orderByDesc('id')->first();
        if ($latestCode) {
            $lastNumber = (int)substr($latestCode->code, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newCode = 'DH' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        $goodsIssue = new GoodsIssue();
        $goodsIssue->code = $newCode;
        $goodsIssue->status = 'pending';
        $goodsIssue->approved_by = null;
        $goodsIssue->customer_id = $customerId;
        $goodsIssue->save();

        foreach ($cartData as $item) {
            $goodsIssueDetail = new GoodsIssueDetail();
            $goodsIssueDetail->goods_issue_id = $goodsIssue->id;
            $goodsIssueDetail->product_id = $item['product_id'];
            $goodsIssueDetail->quantity = $item['quantity'];
            $goodsIssueDetail->unit_price = $item['unit_price'];
            $goodsIssueDetail->discount = $item['discount'];
            $goodsIssueDetail->save();
        }
        DB::commit();
        // Trả về với thông báo thành công
        return redirect()->route('goodsissues.index')->with('success', 'Đơn hàng đã được tạo thành công.');
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
