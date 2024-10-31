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
        $data = $this->product::where('name', 'like', '%' . $key . '%')->get();
        if ($data->count() > 0) {
            $html = '';
            foreach ($data as $item) {
                $imageUrl = $item->images->isNotEmpty() ? asset('upload/' . $item->images->first()->url) : asset('upload/no-image.png');
                $html .= '<div class="search-result-item">';
                $html .= '<img src="' . $imageUrl . '" alt="">';
                $html .= '<div>';
                $html .= '<h6 data-id ="' . $item->id . '" style="display:none"></h6>';
                $html .= '<h4 data-name="' . $item->name . '">' . 'Tên sản phẩm: ' . $item->name . '</h4>'; // Sử dụng e() để escape các ký tự đặc biệt
                $html .= '<p data-code="' . $item->code . '">' . 'Mã sản phẩm: ' . $item->code . '</p>';
                $html .= '</div>';
                $html .= '</div>';
            }
            return response($html);
        } else {
            return response(`<p>Không tìm thấy sản phẩm!</p>`);
        }
    }

    public function ajaxSearchBatch(Request $request)
    {
        $key = $request->input('key');
        $products = $this->product::where('name', 'like', '%' . $key . '%')->get();

        if ($products->count() > 0) {
            $html = '';
            foreach ($products as $product) {
                $imageUrl = $product->images->isNotEmpty() ? asset('upload/' . $product->images->first()->url) : asset('upload/no-image.png');
                $html .= '<div class="search-result-item">';
                $html .= '<img src="' . $imageUrl . '" alt="">';
                $html .= '<div>';
                $html .= '<h6 data-id="' . $product->id . '" style="display:none"></h6>';
                $html .= '<h4 data-name="' . $product->name . '">' . 'Tên sản phẩm: ' . $product->name . '</h4>';
                $html .= '<p data-code="' . $product->code . '">' . 'Mã sản phẩm: ' . $product->code . '</p>';

                $html .= '</div>';
                $html .= '</div>';
            }
            return response($html);
        } else {
            return response('<p>Không tìm thấy sản phẩm</p>');
        }
    }

    private function fetchLocationById($locationId)
    {
        return Location::find($locationId);
    }

    private function fetchAllWarehouseWithLocation()
    {
        return Warehouse::all();
    }

    private function getClosestWarehouse($customerLocation, $warehouses)
    {
        $client = new Client();
        $apiKey = env('OPENROUTESERVICE_API_KEY');
        $closestWarehouse = null;
        $shortestDistance = PHP_FLOAT_MAX;

        foreach ($warehouses as $warehouse) {
            $warehouseLocation = $warehouse->location;
            try {
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

                    $responseBody = $response->getBody()->getContents();

                    $data = json_decode($responseBody, true);
                } catch (\GuzzleHttp\Exception\RequestException $e) {
                    return response()->json(['error' => 'API request failed'], 500);
                } catch (\Exception $e) {
                    return response()->json(['error' => 'An error occurred'], 500);
                }
                $data = json_decode($response->getBody(), true);
                if (isset($data['routes'][0]['summary']['distance'])) {
                    $distance = $data['routes'][0]['summary']['distance'] / 1000;

                    if ($distance < $shortestDistance) {
                        $shortestDistance = $distance;
                        $closestWarehouse = $warehouse;
                    }
                    // info($shortestDistance);
                    // info($closestWarehouse);
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return $closestWarehouse;
    }

    public function getBatches(Request $request)
    {
        $productsData = json_decode($request->input('productsData'), true);
        $batchesByProduct = [];

        foreach ($productsData as $productData) {
            $productId = $productData['productId'];
            $requiredQuantity = $productData['quantity'];
            $customerLocationId = $productData['locationId'];

            $customerLocation = $this->fetchLocationById($customerLocationId);
            $warehouses = $this->fetchAllWarehouseWithLocation();

            $closestWarehouse = $this->getClosestWarehouse($customerLocation, $warehouses);
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
                ->where('warehouses.id', $closestWarehouse->id)
                ->where('inventories.quantity_available', '>', 0)
                ->orderBy('batches.expiry_date', 'asc')
                ->get();
            info($batches);
            //LOG.info: [{"product_id":6,"name":"Mi Hao Hao","batch_id":28,"expiry_date":"2024-10-26","manufacturing_date":"2024-10-04","quantity_available":200,"warehouse_id":7}]
            $selectedBatches = [];
            $totalSelectedQuantity = 0;

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

                if ($totalSelectedQuantity >= $requiredQuantity) {
                    break;
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
