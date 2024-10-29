<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\AssignOp\Pow;

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

    public function getBatches(Request $request)
    {
        // Fetch product ID and required quantity from the request
        $productId = $request->input('product_id');
        $requiredQuantity = $request->input('quantity');

        // Check if the necessary inputs are provided
        if (!$productId || !$requiredQuantity) {
            return response()->json(['error' => 'Product ID and quantity are required.'], 400);
        }

        // Fetch all batches for the specified product that have available quantity, ordered by expiry date
        $batches = $this->product::select(
            'products.id as product_id',
            'products.name',
            'batches.id as batch_id',
            'batches.expiry_date',
            'batches.manufacturing_date',
            'inventories.quantity_available'
        )
            ->join('batches', 'products.id', '=', 'batches.product_id') // Join products and batches
            ->join('inventories', 'batches.id', '=', 'inventories.batch_id') // Join batches and inventories
            ->where('products.id', $productId)
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

            // Add this batch to the selected list with the amount to be taken
            $selectedBatches[] = [
                'batch_id' => $batch->batch_id,
                'quantity' => $quantityToTake,
                'expiry_date' => $batch->expiry_date,
                'manufacturing_date' => $batch->manufacturing_date,
            ];

            // Increment the total selected quantity
            $totalSelectedQuantity += $quantityToTake;

            // Break out of the loop if we have met the required quantity
            if ($totalSelectedQuantity >= $requiredQuantity) {
                break;
            }
        }

        // Return the selected batches as a JSON response
        return response()->json(['batches' => $selectedBatches]);
    }
}
