<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductValue;
use App\Models\Unit;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected $product;
    protected $category;
    protected $unit;
    public function __construct(Product $product, Category $category, Unit $unit)
    {
        $this->product = $product;
        $this->category = $category;
        $this->unit = $unit;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = $this->product->latest("id")->paginate(5);
        $categories = $this->category->all();
        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // $category = $this->category->findOrFail($request->category_id);
        // $attributes = $category->attributes()->with('attributeValues')->get();
        $categories = $this->category->all();
        $units = $this->unit->all();
        return view('admin.products.create', compact('units',  'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $dataCreate = $request->only([
            'code',
            'name',
            'category_id',
            'unit_id',
            'address',
            'refrigerated'
        ]);
        $dataCreate["status"] = "active";

        if ($request->hasFile('image')) {
            $dataCreate["image"] = $this->product->saveImage($request);
        }

        $product = $this->product->create($dataCreate);

        if (isset($dataCreate["image"])) {
            $product->images()->create(["url" => $dataCreate["image"]]);
        }
        if ($request->has('attributes_values') && is_array($request->attributes_values)) {
            foreach ($request->attributes_values as $attributeValueId) {
                ProductValue::create([
                    "product_id" => $product->id,
                    "attribute_value_id" => $attributeValueId
                ]);
            }
        }

        return to_route('products.index')->with(["message" => "Thêm hàng hóa thành công!"]);
    }
    public function getAttributesByCategory($id)
    {
        $category = $this->category->with('attributes.attributeValues')->find($id);

        if (!$category) {
            return response()->json(['error' => 'Danh mục không tồn tại.'], 404);
        }

        $attributes = $category->attributes()->with('attributeValues')->get();

        return response()->json($attributes);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function setPrice()
    {
        $products = $this->product->latest("id")->paginate(5);
        $categories = $this->category->all();
        return view('admin.products.set-price', compact('products', 'categories'));
    }

    public function filterByCategory(Request $request)
    {
        $categoryId = $request->category_id;
        if ($categoryId == 0) {
            $products = Product::paginate(5);
        } else {
            $products = Product::where('category_id', $categoryId)->paginate(5);
        }
        $productData = [];
        foreach ($products as $product) {
            info($products);

            $productData[] = [
                'image' => $product->images->count() > 0 ? asset('upload/' . $product->images->first()->url) : asset('upload/no-image.png'),
                'name' => $product->name,
                'code' => $product->code,
                'unit' => $product->getUnitName(),
                'status' => $product->status == 'active' ? 'Còn hàng' : ($product->status == 'out_of_stock' ? 'Ngừng hoạt động' : 'Ngừng kinh doanh'),
                'refrigerated' => $product->refrigerated === 1 ? 'Bảo quản lạnh' : 'Điều kiện thường',
                'created_at' => $product->created_at,
                'id' => $product->id
            ];
        }


        return response()->json([
            'products' => $productData
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = $this->product->findOrFail($id);
        $categories = $this->category->all();
        $units = $this->unit->all();
        return view('admin.products.edit', compact('product', 'categories', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        $product = $this->product->findOrFail($id);
        $product->update([
            'code' => $request->code,
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'unit_id' => $request->unit_id,
            'refrigerated' => $request->refrigerated,
            'status' => $request->status
        ]);
        $currentImage = $product->images->count() > 0 ? $product->images->first()->url : '';
        info($currentImage);
        $imageUpdate = $this->product->updateImage($request, $currentImage);
        info($imageUpdate);
        $product->images()->delete();
        $product->images()->create(['url' => $imageUpdate]);
        $page = $request->get('page', 1);
        DB::commit();
        return to_route('products.index', ['page' => $page])->with(['message' => 'Cập nhật sản phẩm thành công!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
