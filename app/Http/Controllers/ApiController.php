<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Location;
use App\Models\Product;
use App\Models\Warehouse;
use Barryvdh\Debugbar\Facades\Debugbar as FacadesDebugbar;
use Barryvdh\Debugbar\Twig\Extension\Debug;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\AssignOp\Pow;
use Debugbar;
use DebugBar\DebugBar as DebugBarDebugBar;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
                });
            }])
            ->get();

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

                // Check if distance data is available
                if (isset($data['routes'][0]['summary']['distance'])) {
                    $distance = $data['routes'][0]['summary']['distance'] / 1000; // Distance in km

                    // Add warehouse and distance to array
                    $warehousesWithDistance[] = [
                        'warehouse' => $warehouse,
                        'distance' => $distance
                    ];
                }
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                // Log the error for debugging, continue to the next warehouse
                info('OpenRouteService API request failed: ' . $e->getMessage());
            } catch (\Exception $e) {
                info('Error calculating distance: ' . $e->getMessage());
            }
        }

        // Sort warehouses by distance in ascending order (closest first)
        usort($warehousesWithDistance, function ($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });
        info($warehousesWithDistance);
        return $warehousesWithDistance;
    }

    public function getBatches(Request $request)
    {
        $productsData = json_decode($request->input('productsData'), true);
        $batchesByProduct = [];

        foreach ($productsData as $productData) {
            $productId = $productData['productId'];
            $requiredQuantity = $productData['quantity'];
            $customerLocationId = $productData['locationId'];

            // Fetch customer location and all warehouses
            $customerLocation = $this->fetchLocationById($customerLocationId);
            $warehouses = $this->fetchAllWarehouseWithLocation(); // Fetch all warehouses from the database

            // Sort warehouses by proximity
            $warehousesByProximity = $this->getClosestWarehouses($customerLocation, $warehouses); // Pass both arguments

            $selectedBatches = [];
            $totalSelectedQuantity = 0;

            // Loop through each warehouse by proximity to find batches
            foreach ($warehousesByProximity as $warehouseData) {
                $warehouse = $warehouseData['warehouse'];
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

                    $selectedBatches[] = [
                        'batch_id' => $batch->batch_id,
                        'quantity' => $quantityToTake,
                        'expiry_date' => $batch->expiry_date,
                        'manufacturing_date' => $batch->manufacturing_date,
                        'warehouse' => $batch->warehouse_id
                    ];

                    $totalSelectedQuantity += $quantityToTake;

                    // Stop if required quantity has been met
                    if ($totalSelectedQuantity >= $requiredQuantity) {
                        break 2; // Exit both loops
                    }
                }
            }

            $batchesByProduct[] = [
                'productId' => $productId,
                'batches' => $selectedBatches,
            ];
        }

        return response()->json(['batches' => $batchesByProduct]);
    }
}
