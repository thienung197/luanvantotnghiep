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
        // Lấy sản phẩm và batches kèm theo quantity từ bảng inventory
        $data = $this->product::select(
            'products.*',
            'batches.id as batch_id', // Select the batch ID explicitly
            'batches.expiry_date',
            'batches.manufacturing_date',
            'inventories.quantity_available'
        )
            ->join('batches', 'products.id', '=', 'batches.product_id') // Kết nối giữa bảng products và batches
            ->join('inventories', 'batches.id', '=', 'inventories.batch_id') // Kết nối giữa bảng batches và inventories
            ->where('products.name', 'like', '%' . $key . '%')
            ->where('inventories.quantity_available', '>', 0)
            ->orderByRaw('CASE WHEN batches.expiry_date IS NOT NULL THEN batches.expiry_date ELSE batches.created_at END ASC')
            ->get();
        dump($data);

        if ($data->count() > 0) {
            $html = '';
            foreach ($data as $item) {
                $batch_id = $item->batch_id;
                // dump($batch_id);
                // Lấy các thông tin từ bảng batches (từ join) trực tiếp:
                $expiryDate = $item->expiry_date; // Lấy ngày hết hạn từ lô hàng
                $quantityAvailable = $item->quantity_available; // Lấy số lượng có sẵn từ inventory
                $manufacturing_date = $item->manufacturing_date; // Lấy giá từ batch

                // Chỉ chọn lô hàng tốt nhất theo FIFO (ưu tiên gần hết hạn)
                // $bestBatch = $item->first();
                // // dump($bestBatch);
                // if (!$bestBatch) continue; // Nếu không có lô hàng phù hợp, bỏ qua sản phẩm

                $imageUrl = $item->images->isNotEmpty() ? asset('upload/' . $item->images->first()->url) : asset('upload/no-image.png');

                $html .= '<div class="search-result-item">';
                $html .= '<img src="' . $imageUrl . '" alt="">';
                $html .= '<div>';
                $html .= '<h6 data-id="' . $item->id . '" style="display:none"></h6>';
                $html .= '<h4 data-name="' . $item->name . '">' . 'Tên sản phẩm: ' . $item->name . '</h4>';
                $html .= '<p data-code="' . $item->code . '">' . 'Mã sản phẩm: ' . $item->code . '</p>';
                $html .= '<h4 style="display:none;" class="product_manufacturing_date" data-manufacturing="' . $manufacturing_date . '"></h4>';
                $html .= '<h4 style="display:none;" class="product_expiry_date" data-expiry="' . $expiryDate . '"></h4>';
                $html .= '<h4 style="display:none;" class="product_quantity_available" data-quantity="' . $quantityAvailable . '"></h4>';
                $html .= '<h4 style="display:none;" class="product_batch_id" data-batch="' . $item->batch_id . '"></h4>';
                $html .= '</div>';
                $html .= '</div>';
                break;
            }
            return response($html);
        } else {
            return response('<p>Không tìm thấy sản phẩm</p>');
        }
    }
}
