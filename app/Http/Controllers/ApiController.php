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

                    // Log raw response content for debugging
                    $responseBody = $response->getBody()->getContents();
                    // info("Response: " . $responseBody);

                    // Parse the response body as JSON
                    $data = json_decode($responseBody, true);
                } catch (\GuzzleHttp\Exception\RequestException $e) {
                    // Log the request exception error
                    // info('RequestException: ' . $e->getMessage());
                    return response()->json(['error' => 'API request failed'], 500);
                } catch (\Exception $e) {
                    // Log general errors
                    // info('Exception: ' . $e->getMessage());
                    return response()->json(['error' => 'An error occurred'], 500);
                }
                $data = json_decode($response->getBody(), true);
                if (isset($data['routes'][0]['summary']['distance'])) {
                    $distance = $data['routes'][0]['summary']['distance'] / 1000; // Convert meters to kilometers

                    if ($distance < $shortestDistance) {
                        $shortestDistance = $distance;
                        $closestWarehouse = $warehouse;
                    }
                    info($shortestDistance);
                    info($closestWarehouse);
                }
            } catch (\Exception $e) {
                // Handle API call failure, log the error if needed
                continue;
            }
        }

        return $closestWarehouse;
    }

    public function getBatches(Request $request)
    {
        // Fetch products data from the request
        $productsData = json_decode($request->input('productsData'), true);

        // Initialize an array to hold the results for each product
        $batchesByProduct = [];

        // Loop through each product's data
        foreach ($productsData as $productData) {
            $productId = $productData['productId'];
            $requiredQuantity = $productData['quantity'];
            $customerLocationId = $productData['locationId'];

            // Check if the necessary inputs are provided
            if (!$productId || !$requiredQuantity) {
                return response()->json(['error' => 'Product ID and quantity are required for each product.'], 400);
            }

            $customerLocation = $this->fetchLocationById($customerLocationId);
            $warehouses = $this->fetchAllWarehouseWithLocation();

            // Log customer location for debugging purposes

            $closestWarehouse = $this->getClosestWarehouse($customerLocation, $warehouses);

            if (!$closestWarehouse) {
                return response()->json(['error' => 'No closest warehouse found.'], 404);
            }

            // Fetch all batches for the specified product that have available quantity, ordered by expiry date
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
                ->where('warehouses.id', $closestWarehouse->id) // Sử dụng cú pháp đối tượng
                ->where('inventories.quantity_available', '>', 0)
                ->orderByRaw('CASE WHEN batches.expiry_date IS NOT NULL THEN batches.expiry_date ELSE batches.created_at END ASC')
                ->get();

            // Initialize variables for accumulating the selected batches
            $selectedBatches = [];
            $totalSelectedQuantity = 0;

            // Loop through the fetched batches until the required quantity is fulfilled
            foreach ($batches as $batch) {
                $availableQuantity = $batch->quantity_available;

                // Calculate how much to take from this batch
                $quantityToTake = min($availableQuantity, $requiredQuantity - $totalSelectedQuantity);

                // If quantityToTake is 0, it means we have fulfilled the requirement for this product
                if ($quantityToTake <= 0) {
                    break; // Break out of the loop if the requirement is already met
                }

                // Add this batch to the selected list with the amount to be taken
                $selectedBatches[] = [
                    'batch_id' => $batch->batch_id,
                    'quantity' => $quantityToTake,
                    'expiry_date' => $batch->expiry_date,
                    'manufacturing_date' => $batch->manufacturing_date,
                    'warehouse' => $batch->warehouse_id // Assigning the warehouse ID from the fetched batch
                ];

                // Increment the total selected quantity
                $totalSelectedQuantity += $quantityToTake;

                // Break out of the loop if we have met the required quantity
                if ($totalSelectedQuantity >= $requiredQuantity) {
                    break;
                }
            }

            // Store the selected batches for this product
            $batchesByProduct[] = [
                'productId' => $productId,
                'batches' => $selectedBatches,
            ];
        }
        // Return the selected batches as a JSON response
        return response()->json(['batches' => $batchesByProduct]);
    }
}
