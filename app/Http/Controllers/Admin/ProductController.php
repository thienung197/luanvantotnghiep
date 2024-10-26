<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductValue;
use App\Models\Unit;
use Illuminate\Http\Request;

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
        $category = $this->category->findOrFail($request->category_id);
        $attributes = $category->attributes()->with('attributeValues')->get();
        // $categories = $this->category->all();
        $units = $this->unit->all();
        return view('admin.products.create', compact('units', 'attributes', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $dataCreate = $request->all();
        $dataCreate["status"] = "active";
        $dataCreate["image"] = $this->product->saveImage($request);
        $product = $this->product->create($dataCreate);
        $product->images()->create(["url" => $dataCreate["image"]]);
        if (isset($request->attributes_values) && is_array($request->attributes_values)) {
            foreach ($request->attributes_values as $attributeValueId) {
                ProductValue::create([
                    "product_id" => $product->id,
                    "attribute_value_id" => $attributeValueId
                ]);
            }
        }
        return to_route('products.index')->with(["message" => "Thêm hàng hóa thành công!"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
