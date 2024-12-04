<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\GoodsIssue;
use App\Models\GoodsIssueDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Str;

class CustomerDashboardController extends Controller
{
    protected $category;
    protected $product;
    public function __construct(Category $category, Product $product)
    {
        $this->category = $category;
        $this->product = $product;
    }
    public function index()
    {
        $categories = Category::all();

        $categoriesWithProducts = Category::with(['products' => function ($query) {
            $query->paginate(16);
        }])->get();
        $allProducts = Product::with(['batches.inventories', 'images'])
            ->paginate(16);

        $allProductsWithStock = $allProducts->items();

        $allProductsWithStock = collect($allProductsWithStock)->map(function ($product) {
            $totalStock = $product->batches->sum(function ($batch) {
                return $batch->inventories->sum('quantity_available');
            });

            return (object)[
                'id' => $product->id,
                'name' => $product->name,
                'selling_price' => $product->selling_price,
                'images' => $product->images,
                'totalStock' => $totalStock,
            ];
        });

        $paginatedProducts = new LengthAwarePaginator(
            $allProductsWithStock,
            $allProducts->total(),
            $allProducts->perPage(),
            $allProducts->currentPage(),
            ['path' => $allProducts->path()]
        );

        $cart = session()->get('cart', []);

        return view('customers.dashboard', compact('categoriesWithProducts', 'paginatedProducts', 'cart'));
    }

    public function toggleCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->input('product_id');

        // Nếu sản phẩm đã có trong giỏ hàng, thì xóa
        if (in_array($productId, $cart)) {
            $cart = array_diff($cart, [$productId]); // Loại bỏ sản phẩm khỏi giỏ hàng
        } else {
            $cart[] = $productId; // Thêm sản phẩm vào giỏ hàng
        }

        // Lưu lại trạng thái giỏ hàng
        session()->put('cart', $cart);

        // Trả về trạng thái mới
        return response()->json([
            'status' => in_array($productId, $cart) ? 'added' : 'removed',
            'cart' => $cart
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->input('product_id');

        // Xóa sản phẩm khỏi giỏ hàng
        if (($key = array_search($productId, $cart)) !== false) {
            unset($cart[$key]);
            session()->put('cart', array_values($cart)); // Đảm bảo các chỉ số mảng liên tục
        }

        return response()->json(['success' => 'Sản phẩm đã được xóa khỏi giỏ hàng']);
    }
}
