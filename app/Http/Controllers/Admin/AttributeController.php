<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public $attribute;
    public $category;
    public $attributeValue;
    public function __construct(Attribute $attribute, Category $category, AttributeValue $attributeValue)
    {
        $this->attribute = $attribute;
        $this->category = $category;
        $this->attributeValue = $attributeValue;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entries = $request->input('entries', 5);
        $search = $request->input('search');
        $categories = $this->category->all();
        $attributes = Attribute::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->latest('id')->paginate($entries);
        return view('admin.attributes.index', compact('attributes', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $dataCreate = $request->all();
        $this->attribute->create($dataCreate);
        return to_route('attributes.index')->with(['message' => 'Tạo thuộc tính thành công!']);
    }

    public function storeValue(Request $request, $attributeId)
    {
        $dataCreate = $request->all();
        $dataCreate['attribute_id'] = $attributeId;
        $this->attributeValue->create($dataCreate);
        return to_route('attributes.edit', $attributeId)->with(['message' => 'Tạo giá trị thành công!']);
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
    public function edit(Request $request, string $id)
    {
        $entries = $request->input('entries', 5);
        $search = $request->input('search');
        $attribute = $this->attribute->with('attributeValues')->findOrFail($id);
        $attributeValues = $attribute->attributeValues;
        $attributeValues = AttributeValue::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->latest('id')->paginate($entries);;
        $attributeId = $attribute->id;
        return view('admin.attributes.edit', compact('attribute', 'attributeId', 'attributeValues'));
    }
    public function editAttribute(string $id)
    {
        $attribute = $this->attribute->findOrFail($id);
        return response()->json(['status' => 200, 'attribute' => $attribute]);
    }

    public function editAttributeValue(string $attribute, $attributeValue)
    {
        $attributeValue = $this->attributeValue->findOrFail($attributeValue);
        $attribute = $this->attribute->findOrFail($attribute);
        return response()->json(['status' => 200, 'attributeValue' => $attributeValue, 'attribute' => $attribute]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateAttribute(Request $request, string $id)
    {
        $dataUpdate = $request->all();
        $attribute = $this->attribute->findOrFail($id);
        $attribute->update($dataUpdate);
        return to_route('attributes.index')->with('message', 'Cập nhật thuộc tính thành công!');
    }

    public function updateAttributeValue(Request $request,  string $attribute, string $attributeValue)
    {
        $dataUpdate = $request->all();
        $attributeValueModel = $this->attributeValue->findOrFail($attributeValue);
        $attributeValueModel->update($dataUpdate);
        return to_route('attributes.edit', $attribute)->with('message', 'Cập nhật giá trị thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attribute = $this->attribute->findOrFail($id);
        $attribute->delete();
        return to_route('attributes.index')->with(['message' => 'Xóa thuộc tính thành công!']);
    }

    public function destroyValues($attributeId, $valueId)
    {
        $attributeValue = $this->attributeValue->findOrFail($valueId);
        $attributeValue->delete();
        return to_route('attributes.edit', $attributeId)->with(['message' => 'Xóa giá trị thành công!']);
    }
}
