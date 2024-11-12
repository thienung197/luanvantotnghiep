<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\GoodsIssue;
use App\Models\GoodsIssueDetail;
use App\Models\Product;
use Illuminate\Http\Request;
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
        $allProducts = Product::paginate(16);
        return view('customers.dashboard', compact('categoriesWithProducts', 'allProducts'));
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
