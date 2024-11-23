<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Customer;
use App\Models\GoodsIssueBatch;
use App\Models\Inventory;
use App\Models\Location;
use App\Models\Product;
use App\Models\RestockRequest;
use App\Models\RestockRequestDetail;
use App\Models\Warehouse;
use Barryvdh\Debugbar\Facades\Debugbar as FacadesDebugbar;
use Barryvdh\Debugbar\Twig\Extension\Debug;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\AssignOp\Pow;
use Debugbar;
use DebugBar\DebugBar as DebugBarDebugBar;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    protected $product;
    protected $batch;
    protected $inventory;

    public function __construct(Product $product, Batch $batch, Inventory $inventory)
    {
        $this->product = $product;
        $this->batch = $batch;
        $this->inventory = $inventory;
    }
    public function ajaxSearchProduct(Request $request)
    {
        $key = $request->input('key');
        $products = $this->product->where('name', 'like', '%' . $key . '%')->get();
        if ($products->count() > 0) {
            $html = '';
            foreach ($products as $item) {
                $imageUrl = $item->images->isNotEmpty() ? asset('upload/' . $item->images->first()->url) : asset('upload/no-image.png');
                $html .= '<div class="search-result-item">';
                $html .= '<img src="' . $imageUrl . '" alt="">';
                $html .= '<div>';
                $html .= '<h6 data-id ="' . $item->id . '" style="display:none"></h6>';
                $html .= '<h4 data-name="' . $item->name . '">' . 'Tên sản phẩm: ' . $item->name . '</h4>'; // Sử dụng e() để escape các ký tự đặc biệt
                $html .= '<p data-code="' . $item->code . '">' . 'Mã sản phẩm: ' . $item->code . '</p>';
                $html .= '<p class="ajax-product-unit" style="display:none" data-unit="' . $item->getUnitName() . '">' . 'Đơn vị tính: ' . $item->getUnitName() . '</p>';
                $html .= '<p class="ajax-product-price" style="display:none" data-price="' . $item->selling_price . '">' . 'Giá sản phẩm : ' . $item->selling_price . '</p>';

                $html .= '</div>';
                $html .= '</div>';
            }
            return response($html);
        } else {
            return response(`<p>Không tìm thấy sản phẩm!</p>`);
        }
    }

    public function ajaxSearchProductTable(Request $request)
    {
        $key = $request->input('key');
        $products = $this->product->where('name', 'like', '%' . $key . '%')->get();

        if ($products->count() > 0) {
            $html = '';
            foreach ($products as $product) {
                $html .= '<tr>';
                $html .= '<td>' . e($product->name) . '</td>';
                $html .= '<td>' . e($product->code) . '</td>';
                $html .= '<td>' . e($product->getUnitName()) . '</td>';
                $html .= '<td>' . ($product->status == 'active' ? 'Còn hàng' : ($product->status == 'out_of_stock' ? 'Ngừng hoạt động' : 'Ngừng kinh doanh')) . '</td>';
                $html .= '<td>' . ($product->refrigerated === 1 ? 'Bảo quản lạnh' : 'Điều kiện thường') . '</td>';
                $html .= '<td><input type="number" class="selling-price-input" data-id="' . $product->id . '" value="' . e($product->selling_price) . '"></td>';
                $html .= '</tr>';
            }
            return response($html);
        } else {
            return response('<tr><td colspan="6">Không tìm thấy sản phẩm!</td></tr>');
        }
    }

    public function ajaxSearchGoodsIssueBatch(Request $request)
    {
        $key = $request->input('key');
        $warehouseId = $request->input('warehouse_id');
        $goodsIssueBatches = GoodsIssueBatch::where('code', 'like', '%' . $key . '%')
            ->where('warehouse_id', $warehouseId)
            ->where('status', 'processing')
            ->get();
        if ($goodsIssueBatches->count() > 0) {
            $html = '';
            foreach ($goodsIssueBatches as $batch) {
                $productId = Batch::where('id', $batch->batch_id)->value('product_id');
                $product = Product::where('id', $productId)->first();
                $html .= '<div class="search-result-item">';
                $html .= '<div>';
                $html .= '<h4 class="goods-issue-batch-code" data-goods-issue-batch-code="' . $batch->code . '">' . 'Mã đơn hàng: ' . e($batch->code) . '</h4>';
                $html .= '<p class="status" data-status="' . $batch->status . '">' . 'Trạng thái: ' . e($batch->status) . '</p>';
                $html .= '<p class="created-at" data-created_at="' . $batch->created_at . '">' . 'Ngày tạo: ' . e($batch->created_at) . '</p>';
                $html .= '<p style="display:none" class="product-code" data-product-code="' . $product->code . '">' . 'Mã hàng: ' . e($product->code) . '</p>';
                $html .= '<p style="display:none" class="product-name" data-product-name="' . $product->name . '">' . 'Tên hàng: ' . e($product->name) . '</p>';
                $html .= '<p style="display:none" style="display:none" class="product-quantity" data-product-quantity="' . $batch->quantity . '">' . 'Số lượng: ' . e($batch->quantity) . '</p>';
                $html .= '<p style="display:none" class="product-selling-price" data-product-selling-price="' . $batch->unit_price . '">' . 'Giá bán: ' . e($batch->unit_price) . '</p>';
                $html .= '<p style="display:none" class="product-discount" data-product-discount="' . $batch->discount . '">' . 'Giảm giá: ' . e($batch->discount) . '</p>';
                $html .= '</div>';
                $html .= '</div>';
            }
            return response($html);
        } else {
            return response('<p>Không tìm thấy đơn hàng chưa giao!</p>');
        }
    }

    public function updateProductPrice(Request $request)
    {
        $product = Product::find($request->product_id);
        info($product);
        info($request->selling_price);
        if ($product) {
            $product->selling_price = $request->selling_price;
            $product->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phâm!']);
    }

    public function ajaxSearchProductByWarehouse(Request $request)
    {
        $key = $request->input('key');
        $warehouseId = $request->input('warehouse_id');

        // Lấy danh sách sản phẩm dựa vào tên
        $products = $this->product
            ->where('name', 'like', '%' . $key . '%')
            ->with(['batches' => function ($query) use ($warehouseId) {
                $query->whereHas('inventories', function ($inventoryQuery) use ($warehouseId) {
                    $inventoryQuery->where('warehouse_id', $warehouseId);
                })
                    ->with(['inventories' => function ($inventoryQuery) use ($warehouseId) {
                        $inventoryQuery->where('warehouse_id', $warehouseId)
                            ->select('batch_id', DB::raw('SUM(available_quantity) as total_available_quantity'))
                            ->groupBy('batch_id');
                    }]);
            }])
            ->get();
        info($products);

        if ($products->count() > 0) {
            $html = '';
            foreach ($products as $item) {
                $imageUrl = $item->images->isNotEmpty() ? asset('upload/' . $item->images->first()->url) : asset('upload/no-image.png');
                $html .= '<div class="search-result-item">';
                $html .= '<img src="' . $imageUrl . '" alt="">';
                $html .= '<div>';
                $html .= '<h6 class="product-id" data-id ="' . $item->id . '" style="display:none"></h6>';
                $html .= '<h4 data-name="' . $item->name . '">' . 'Tên sản phẩm: ' . e($item->name) . '</h4>';
                $html .= '<p class="product-code" data-code="' . $item->code . '">' . 'Mã sản phẩm: ' . e($item->code) . '</p>';
                $html .= '<p class="ajax-product-unit" style="display:none" data-unit="' . $item->getUnitName() . '">' . 'Đơn vị tính: ' . e($item->getUnitName()) . '</p>';
                $html .= '<p class="ajax-product-price" style="display:none" data-price="' . $item->selling_price . '">' . 'Giá sản phẩm : ' . e($item->selling_price) . '</p>';

                if ($item->batches->isNotEmpty()) {
                    $html .= '<div class="batch-list">';
                    $html .= '<h5>Các lô hàng:</h5>';
                    foreach ($item->batches as $batch) {
                        $html .= '<p data-batch-id="' . $batch->id . '" data-batch-code="' . $batch->code . '">';
                        $html .= 'Mã lô: ' . e($batch->code);
                        $html .= '</p>';
                    }
                    $html .= '</div>';
                } else {
                    $html .= '<p>Không có lô hàng khả dụng trong kho.</p>';
                }

                $html .= '</div>';
                $html .= '</div>';
            }
            info($html);

            return response($html);
        } else {
            return response('<p>Không tìm thấy sản phẩm!</p>');
        }
    }

    public function ajaxSearchProductAndInventoryByWarehouse(Request $request)
    {
        $key = $request->input('key');
        $warehouseId = $request->input('warehouse_id');
        info($warehouseId);
        $products = $this->product
            ->where('name', 'like', '%' . $key . '%')
            ->with([
                'images',
                'unit',
                'batches' => function ($query) use ($warehouseId) {
                    $query->whereHas('inventories', function ($inventoryQuery) use ($warehouseId) {
                        $inventoryQuery->where('warehouse_id', $warehouseId);
                    })
                        ->with(['inventories' => function ($inventoryQuery) use ($warehouseId) {
                            $inventoryQuery->where('warehouse_id', $warehouseId);
                        }]);
                }
            ])
            ->get()
            ->map(function ($product) {
                // Calculate total available quantity across all batches for each product
                $totalQuantityAvailable = $product->batches->sum(function ($batch) {
                    return $batch->inventories->sum('quantity_available');
                });

                $remainingStockLevel = $product->target_stock_level - $totalQuantityAvailable;

                $product->total_quantity_available = $totalQuantityAvailable;
                if ($remainingStockLevel > 0) {
                    $product->remaining_stock_level = $remainingStockLevel;
                } else {
                    $product->remaining_stock_level = 0;
                }
                $product->image_url = $product->images->first()->url ?? null;

                return $product;
            });
        info($products);
        if ($products->count() > 0) {
            $html = '';
            foreach ($products as $item) {
                $imageUrl = $item->images->first() ? $item->images->first()->url : 'No Image';
                $itemId = $item->id;
                $itemCode = $item->code;
                $itemName = $item->name;
                $unitName = $item->unit->name;
                $sellingPrice = $item->selling_price;
                $totalStock = $item->total_quantity_available;
                $suggestedQuantity = $item->remaining_stock_level;
                $imageUrl = $item->images->isNotEmpty() ? asset('upload/' . $item->images->first()->url) : asset('upload/no-image.png');
                $html .= '<div class="search-result-item">';
                $html .= '<img src="' . $imageUrl . '" alt="">';
                $html .= '<div>';
                $html .= '<h6 class="product-id" data-id ="' . $itemId . '" style="display:none"></h6>';
                $html .= '<h4 data-name="' . $itemName . '">' . 'Tên sản phẩm: ' . e($itemName) . '</h4>';
                $html .= '<p class="product-code" data-code="' . $itemCode . '">' . 'Mã sản phẩm: ' . e($itemCode) . '</p>';
                $html .= '<p class="ajax-product-unit" style="display:none" data-unit="' . $unitName . '">' . 'Đơn vị tính: ' . e($unitName) . '</p>';
                $html .= '<p class="ajax-product-price" style="display:none" data-price="' . $sellingPrice . '">' . 'Giá sản phẩm : ' . e($sellingPrice) . '</p>';
                $html .= '<p class="ajax-product-inventory-quantity" style="display:none" data-inventory-quantity="' . $totalStock . '">' . 'Giá sản phẩm : ' . e($totalStock) . '</p>';
                $html .= '<p class="ajax-product-suggested-quantity" style="display:none" data-suggested-quantity="' . $suggestedQuantity . '">' . 'Giá sản phẩm : ' . e($suggestedQuantity) . '</p>';

                // if ($item->batches->isNotEmpty()) {
                //     $html .= '<div class="batch-list">';
                //     $html .= '<h5>Các lô hàng:</h5>';
                //     foreach ($item->batches as $batch) {
                //         $html .= '<p data-batch-id="' . $batch->id . '" data-batch-code="' . $batch->code . '">';
                //         $html .= 'Mã lô: ' . e($batch->code);
                //         $html .= '</p>';
                //     }
                //     $html .= '</div>';
                // } else {
                //     $html .= '<p>Không có lô hàng khả dụng trong kho.</p>';
                // }

                $html .= '</div>';
                $html .= '</div>';
            }

            return response($html);
        } else {
            return response('<p>Không tìm thấy sản phẩm!</p>');
        }
    }


    public function getInventoryQuantity(Request $request)
    {
        $batchId = $request->input('batch_id');
        $warehouseId = $request->input('warehouse_id');
        $inventory = Inventory::where('batch_id', $batchId)
            ->where('warehouse_id', $warehouseId)
            ->first();

        if ($inventory) {
            return response()->json([
                'quantity_available' => $inventory->quantity_available
            ]);
        } else {
            return response()->json([
                'quantity_available' => 0
            ]);
        }
    }

    //thiet ke cua kiem kho
    public function ajaxSearchBatch(Request $request)
    {
        $key = $request->key;
        $warehouseId = $request->warehouse_id;
        $product_ids = $this->product::where('name', 'like', '%' . $key . '%')->get()->pluck('id');
        $batches = $this->batch::join('inventories', 'batches.id', '=', 'inventories.batch_id')
            ->whereIn('batches.product_id', $product_ids)
            ->where('inventories.warehouse_id', $warehouseId)
            ->get();
        info($batches);
        info($batches->count());
        if ($batches->count() > 0) {
            $html = '';
            $product_id = $batches->first()->product_id;
            $product = Product::where('id', $product_id)->first();
            $price = $product ? $product->selling_price : '0';
            foreach ($batches as $batch) {
                $imageUrl = $product->images->isNotEmpty() ? asset('upload/' . $product->images->first()->url) : asset('upload/no-image.png');
                $inventory = $batch->inventories->first();
                $batch_quantity = $inventory ? $inventory->quantity_available : 0;
                $html .= '<div class="search-result-item">';
                $html .= '<img src="' . $imageUrl . '" alt="">';
                $html .= '<div>';
                $html .= '<h6 class="product-id" data-id ="' . $product->id . '" style="display:none"></h6>';
                $html .= '<h6 class="product-unit" data-unit ="' . $product->getUnitName() . '" style="display:none"></h6>';
                $html .= '<h6 class="product-price" data-price ="' . $price . '" style="display:none"></h6>';
                $html .= '<h6 class="product-batch-quantity" data-batch-quantity ="' . $batch_quantity . '" style="display:none"></h6>';
                $html .= '<h4 data-name="' . $product->name . '">' . 'Tên SP: ' . $product->name . '</h4>';
                $html .= '<div class="d-flex align-items-center justify-content-between">';
                $html .= '<p class="product-code" data-product-code="' . $product->code . '">' . 'Mã SP: ' . $product->code . '</p>';
                $html .= '<p style="margin-left:10px" class="batch-code" data-batch-code="' . $batch->code . '">' . 'Mã Lô: ' . $batch->code . '</p>';
                $html .= '</div>';
                $html .= '<div class="d-flex align-items-center justify-content-between" >';
                $html .= '<p  style="display:none" style="margin-right:90px" data-manufacturing-data="' . $batch->manufacturing_date . '">NSX: ' . $batch->manufacturing_date . '</p>';
                $html .= '<p style="display:none" data-expiry-data="' . $batch->expiry_date . '">HSD: ' . $batch->expiry_date . '</p>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }
            return response($html);
        } else {
            return response(`<p>Không tìm thấy sản phẩm!</p >`);
        }
    }
    //nha kho dung de de xuat hang hoa de nhap hang(do ton kho thap)
    public function getSuggestedProducts(Request $request)
    {
        $warehouseId = $request->warehouse_id;
        operator:
        $products = DB::table('products')
            ->join('batches', 'products.id', '=', 'batches.product_id')
            ->join('inventories', 'batches.id', '=', 'inventories.batch_id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->select(
                'products.id',
                'products.name',
                'products.code',
                'units.name as unit_name',
                'inventories.warehouse_id',
                // 'batches.price as unit_price',
                DB::raw('SUM(inventories.quantity_available) as available_quantity'),
                DB::raw('products.target_stock_level - SUM(inventories.quantity_available) as suggested_quantity'),
                // 'products.minimum_stock_level'
            )
            ->where('inventories.warehouse_id', $warehouseId)
            ->groupBy('products.id', 'products.code', 'products.name', 'units.name', 'inventories.warehouse_id', 'products.target_stock_level', 'products.minimum_stock_level')
            ->havingRaw('SUM(inventories.quantity_available) < products.minimum_stock_level')
            ->get();
        info($products);
        return response($products);
    }
    //cap nhat trang thai khi admin danh gia de nghi nhap hang
    public function updateRestockRequestStatus(Request $request, $id)
    {
        try {
            $restockRequestId = $request->restockRequestId;
            $restockRequestDetail = RestockRequestDetail::findOrFail($id);
            $restockRequestDetail->status = $request->status;
            $restockRequestDetail->save();
            $restockRequestDetails = RestockRequestDetail::where('restock_request_id', $restockRequestDetail->restock_request_id)->get();
            $allPending = $restockRequestDetails->every(function ($detail) {
                return $detail->status === 'pending';
            });
            $somePending = $restockRequestDetails->some(function ($detail) {
                return $detail->status === 'pending';
            });
            $requestStatus = 0;
            if ($allPending) {
                $restockRequestDetail->restockRequest->status = 'pending';
                $requestStatus = 1;
            } elseif ($somePending) {
                $restockRequestDetail->restockRequest->status = 'in_review';
                $requestStatus = 2;
            } else {
                $restockRequestDetail->restockRequest->status = 'reviewed';
                $requestStatus = 3;
            }
            $restockRequestDetail->restockRequest->save();
            return response()->json(['success' => true, 'message' => 'Trạng thái được cập nhập thành công!', 'requestStatus' => $requestStatus]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status'], 500);
        }
    }

    public function loadApprovedProducts()
    {

        // $approvedProducts = Product::whereHas('restockRequestDetails', function ($query) {
        //     $query->whereHas('restockRequest', function ($query) {
        //         $query->where('status', 'approved');
        //     });
        // })->get();
        $approvedProducts = Product::whereHas('restockRequestDetails', function ($query) {
            $query->whereHas('restockRequest', function ($query) {
                $query->where('status', 'approved');
            });
        })->get();
        $products = [];
        foreach ($approvedProducts as $product) {
            $data = [
                'id' => $product->id,
                'code' => $product->code,
                'name' => $product->name,
                'unit' => $product->unit->name,
            ];
            $products[] = $data;
        }

        return response()->json($products);
    }

    // k biet cua cai nao?
    // public function ajaxSearchBatch(Request $request)
    // {
    //     $key = $request->key;
    //     $warehouseId = $request->warehouse_id;
    //     $product_ids = $this->product::where('name', 'like', '%' . $key . '%')->get()->pluck('id');
    //     $batches = $this->batch::join('inventories', 'batches.id', '=', 'inventories.batch_id')
    //         ->whereIn('batches.product_id', $product_ids)
    //         ->where('inventories.warehouse_id', $warehouseId)
    //         ->get();
    //     info($batches);
    //     info($batches->count());
    //     if ($batches->count() > 0) {
    //         $html = '';
    //         $product_id = $batches->first()->product_id;
    //         $product = Product::where('id', $product_id)->first();
    //         $price = $product ? $product->selling_price : '0';
    //         foreach ($batches as $batch) {
    //             $imageUrl = $product->images->isNotEmpty() ? asset('upload/' . $product->images->first()->url) : asset('upload/no-image.png');
    //             $inventory = $batch->inventories->first();
    //             $batch_quantity = $inventory ? $inventory->quantity_available : 0;
    //             $html .= '<div class="search-result-item">';
    //             $html .= '<img src="' . $imageUrl . '" alt="">';
    //             $html .= '<div>';
    //             $html .= '<h6 class="product-id" data-id ="' . $product->id . '" style="display:none"></h6>';
    //             $html .= '<h6 class="product-unit" data-unit ="' . $product->getUnitName() . '" style="display:none"></h6>';
    //             $html .= '<h6 class="product-price" data-price ="' . $price . '" style="display:none"></h6>';
    //             $html .= '<h6 class="product-batch-quantity" data-batch-quantity ="' . $batch_quantity . '" style="display:none"></h6>';
    //             $html .= '<h4 data-name="' . $product->name . '">' . 'Tên SP: ' . $product->name . '</h4>';
    //             $html .= '<div class="d-flex align-items-center justify-content-between">';
    //             $html .= '<p class="product-code" data-product-code="' . $product->code . '">' . 'Mã SP: ' . $product->code . '</p>';
    //             $html .= '<p style="margin-left:10px" class="batch-code" data-batch-code="' . $batch->code . '">' . 'Mã Lô: ' . $batch->code . '</p>';
    //             $html .= '</div>';
    //             $html .= '<div class="d-flex align-items-center justify-content-between" >';
    //             $html .= '<p  style="display:none" style="margin-right:90px" data-manufacturing-data="' . $batch->manufacturing_date . '">NSX: ' . $batch->manufacturing_date . '</p>';
    //             $html .= '<p style="display:none" data-expiry-data="' . $batch->expiry_date . '">HSD: ' . $batch->expiry_date . '</p>';
    //             $html .= '</div>';
    //             $html .= '</div>';
    //             $html .= '</div>';
    //         }
    //         return response($html);
    //     } else {
    //         return response(`<p>Không tìm thấy sản phẩm!</p >`);
    //     }
    // }


    // public function ajaxSearchBatch(Request $request)
    // {
    //     $key = $request->input('key');
    //     $products = $this->product::where('name', 'like', '%' . $key . '%')->get();

    //     if ($products->count() > 0) {
    //         $html = '';
    //         foreach ($products as $product) {
    //             $imageUrl = $product->images->isNotEmpty() ? asset('upload/' . $product->images->first()->url) : asset('upload/no-image.png');
    //             $html .= '<div class="search-result-item">';
    //             $html .= '<img src="' . $imageUrl . '" alt="">';
    //             $html .= '<div>';
    //             $html .= '<h6 data-id="' . $product->id . '" style="display:none"></h6>';
    //             $html .= '<h4 data-name="' . $product->name . '">' . 'Tên sản phẩm: ' . $product->name . '</h4>';
    //             $html .= '<p data-code="' . $product->code . '">' . 'Mã sản phẩm: ' . $product->code . '</p>';

    //             $html .= '</div>';
    //             $html .= '</div>';
    //         }
    //         return response($html);
    //     } else {
    //         return response('<p>Không tìm thấy sản phẩm</p>');
    //     }
    // }

    private function fetchLocationById($locationId)
    {
        return Location::find($locationId);
    }

    private function fetchAllWarehouseWithLocation()
    {
        return Warehouse::all();
    }

    // private function getClosestWarehouse($customerLocation, $warehouses)
    // {
    //     $client = new Client();
    //     $apiKey = env('OPENROUTESERVICE_API_KEY');
    //     $closestWarehouse = null;
    //     $shortestDistance = PHP_FLOAT_MAX;

    //     foreach ($warehouses as $warehouse) {
    //         $warehouseLocation = $warehouse->location;
    //         try {
    //             try {
    //                 $response = $client->post('https://api.openrouteservice.org/v2/directions/driving-car', [
    //                     'headers' => [
    //                         'Authorization' => $apiKey,
    //                         'Content-Type' => 'application/json',
    //                     ],
    //                     'json' => [
    //                         'coordinates' => [
    //                             [(float)$warehouseLocation->longitude, (float)$warehouseLocation->latitude],
    //                             [(float)$customerLocation->longitude, (float)$customerLocation->latitude],
    //                         ],
    //                     ],
    //                 ]);

    //                 $responseBody = $response->getBody()->getContents();

    //                 $data = json_decode($responseBody, true);
    //             } catch (\GuzzleHttp\Exception\RequestException $e) {
    //                 return response()->json(['error' => 'API request failed'], 500);
    //             } catch (\Exception $e) {
    //                 return response()->json(['error' => 'An error occurred'], 500);
    //             }
    //             $data = json_decode($response->getBody(), true);
    //             if (isset($data['routes'][0]['summary']['distance'])) {
    //                 $distance = $data['routes'][0]['summary']['distance'] / 1000;

    //                 if ($distance < $shortestDistance) {
    //                     $shortestDistance = $distance;
    //                     $closestWarehouse = $warehouse;
    //                 }
    //             }
    //         } catch (\Exception $e) {
    //             continue;
    //         }
    //     }

    //     return $closestWarehouse;
    // }

    private function getClosestWarehouses($customerLocation, $warehouses)
    {
        $client = new Client();
        $apiKey = env('OPENROUTESERVICE_API_KEY');
        $warehousesWithDistance = [];

        foreach ($warehouses as $warehouse) {
            $warehouseLocation = $warehouse->location;

            try {
                $response = $client->post('https://api.openrouteservice.org/v2/directions/driving-car', [
                    'headers' => [
                        'Authorization' => $apiKey,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'coordinates' => [
                            [(float)$warehouseLocation->longitude, (float)$warehouseLocation->latitude],
                            [(float)$customerLocation->longitude, (float)$customerLocation->latitude],
                        ],
                    ],
                ]);

                $data = json_decode($response->getBody()->getContents(), true);

                if (isset($data['routes'][0]['summary']['distance'])) {
                    $distance = $data['routes'][0]['summary']['distance'] / 1000;

                    $warehousesWithDistance[] = [
                        'warehouse' => $warehouse, // Trả về cả đối tượng Warehouse
                        'distance' => round($distance, 2), // Làm tròn khoảng cách đến 2 chữ số thập phân
                    ];
                }
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                info('OpenRouteService API request failed: ' . $e->getMessage());
            } catch (\Exception $e) {
                info('Error calculating distance: ' . $e->getMessage());
            }
        }

        // Sắp xếp danh sách nhà kho theo khoảng cách (tăng dần)
        usort($warehousesWithDistance, function ($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

        // Ghi log danh sách nhà kho và khoảng cách (gọn gàng)
        // info('Warehouses with distances:', array_map(function ($item) {
        //     return [
        //         'warehouse_name' => $item['warehouse']->name,
        //         'distance' => $item['distance'],
        //     ];
        // }, $warehousesWithDistance));

        return $warehousesWithDistance;
    }

    public function getBatches(Request $request)
    {
        $productsData = json_decode($request->input('productsData'), true);
        $batchesByProduct = [];
        $groupedBatchDetails = [];
        foreach ($productsData as $productData) {
            $productId = $productData['productId'];
            $requiredQuantity = $productData['quantity'];
            $customerLocationId = $productData['locationId'];
            $unitPrice = $productData['unitPrice'];
            $discount = $productData['discount'];
            $totalPrice = $productData['totalPrice'];

            $customerLocation = $this->fetchLocationById($customerLocationId);
            $warehouses = $this->fetchAllWarehouseWithLocation();

            $warehousesByProximity = $this->getClosestWarehouses($customerLocation, $warehouses);

            // info('Closest warehouses:', array_map(function ($warehouseData) {
            //     return [
            //         'warehouse' => $warehouseData['warehouse']->name,
            //         'distance' => $warehouseData['distance']
            //     ];
            // }, $warehousesByProximity));
            $warehouseDetails = array_map(function ($warehouseData) {
                return [
                    'name' => $warehouseData['warehouse']->name,
                    'location' => $warehouseData['warehouse']->location,
                    'distance' => $warehouseData['distance']
                ];
            }, $warehousesByProximity);

            $selectedBatches = [];
            $totalSelectedQuantity = 0;
            $batchDetails = Product::select(
                'products.id as product_id',
                'products.name as product_name',
                'batches.id as batch_id',
                'batches.code as batch_code',
                'batches.expiry_date',
                'batches.manufacturing_date',
                'inventories.quantity_available',
                'warehouses.id as warehouse_id',
                'warehouses.name as warehouse_name'
            )
                ->join('batches', 'products.id', '=', 'batches.product_id')
                ->join('inventories', 'batches.id', '=', 'inventories.batch_id')
                ->join('warehouses', 'inventories.warehouse_id', '=', 'warehouses.id')
                ->where('products.id', $productId)
                ->where('inventories.quantity_available', '>', 0) // Lọc các lô hàng có số lượng > 0
                ->orderBy('batches.expiry_date', 'asc')
                ->get();
            foreach ($batchDetails as $batch) {
                $productName = $batch['product_name']; // Lấy tên sản phẩm

                // Nếu sản phẩm chưa tồn tại trong mảng, khởi tạo nó
                if (!isset($groupedBatchDetails[$productName])) {
                    $groupedBatchDetails[$productName] = [];
                }

                // Thêm thông tin lô hàng vào mảng của sản phẩm tương ứng
                $groupedBatchDetails[$productName][] = [
                    'batch_id' => $batch['batch_id'],
                    'batch_code' => $batch['batch_code'],
                    'expiry_date' => $batch['expiry_date'],
                    'manufacturing_date' => $batch['manufacturing_date'],
                    'quantity_available' => $batch['quantity_available'],
                    'warehouse_id' => $batch['warehouse_id'],
                    'warehouse_name' => $batch['warehouse_name']
                ];
            }

            // Kết quả sẽ nằm trong $groupedBatchDetails
            info($groupedBatchDetails);
            foreach ($warehousesByProximity as $warehouseData) {
                $warehouse = $warehouseData['warehouse'];
                // if (!is_object($warehouse)) {
                //     info('Invalid warehouse data:', $warehouseData);
                //     continue;
                // }

                $batches = Product::select(
                    'products.id as product_id',
                    'products.name',
                    'batches.id as batch_id',
                    'batches.expiry_date',
                    'batches.manufacturing_date',
                    'inventories.quantity_available',
                    'warehouses.id as warehouse_id'
                )
                    ->join('batches', 'products.id', '=', 'batches.product_id')
                    ->join('inventories', 'batches.id', '=', 'inventories.batch_id')
                    ->join('warehouses', 'inventories.warehouse_id', '=', 'warehouses.id')
                    ->where('products.id', $productId)
                    ->where('warehouses.id', $warehouse->id)
                    ->where('inventories.quantity_available', '>', 0)
                    ->orderBy('batches.expiry_date', 'asc')
                    ->get();

                foreach ($batches as $batch) {
                    $availableQuantity = $batch->quantity_available;
                    $quantityToTake = min($availableQuantity, $requiredQuantity - $totalSelectedQuantity);

                    if ($quantityToTake <= 0) {
                        break;
                    }
                    $warehouseName = Warehouse::where('id', $batch->warehouse_id)->firstOrFail()->name;
                    $batchCode = Batch::where('id', $batch->batch_id)->firstOrFail()->code;
                    $selectedBatches[] = [
                        'batch_id' => $batch->batch_id,
                        'batch_code' => $batchCode,
                        'quantityAvailable' => $availableQuantity,
                        'quantityToTake' => $quantityToTake,
                        'expiry_date' => $batch->expiry_date,
                        'manufacturing_date' => $batch->manufacturing_date,
                        'warehouse' => $warehouseName,
                        'warehouseId' => $batch->warehouse_id,
                        'unitPrice' => $unitPrice,
                        'discount' => $discount,
                        'totalPrice' => $totalPrice
                    ];

                    $totalSelectedQuantity += $quantityToTake;

                    if ($totalSelectedQuantity >= $requiredQuantity) {
                        break 2;
                    }
                }
            }

            $batchesByProduct[] = [
                'productId' => $productId,
                // 'totalQuantityRequired' => ,
                'batches' => $selectedBatches,
            ];
        }

        return response()->json(['batches' => $batchesByProduct, 'warehouseDetails' => $warehouseDetails, 'batchDetails' => $groupedBatchDetails]);
    }
}
