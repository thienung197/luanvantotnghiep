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

        // Dùng items() để lấy collection các sản phẩm
        $allProductsWithStock = $allProducts->items();

        $allProductsWithStock = collect($allProductsWithStock)->map(function ($product) {
            $totalStock = $product->batches->sum(function ($batch) {
                return $batch->inventories->sum('quantity_available');
            });

            return (object)[
                'id' => $product->id,
                'name' => $product->name,
                'selling_price' => $product->selling_price,
                'images' => $product->images, // Thêm images vào dữ liệu
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
        info($paginatedProducts);

        return view('customers.dashboard', compact('categoriesWithProducts', 'paginatedProducts'));
    }

    public function addToCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->input('product_id');
        if (!in_array($productId, $cart)) {
            $cart[] = $productId;
        }
        session()->put('cart', $cart);
        info($cart);
        return response()->json(['success' => 'Sản phẩm đã được thêm vào giỏ hàng']);
    }
}
