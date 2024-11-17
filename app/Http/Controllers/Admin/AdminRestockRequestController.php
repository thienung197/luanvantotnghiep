<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RestockRequest;
use Illuminate\Http\Request;

class AdminRestockRequestController extends Controller
{
    protected $restockRequest;
    protected $product;
    public function __construct(RestockRequest $restockRequest, Product $product)
    {
        $this->restockRequest = $restockRequest;
        $this->product = $product;
    }
    public function index()
    {
        $user = auth()->user();
        $restockRequests = $this->restockRequest::with('warehouse')->latest('id')->get();
        return view('admin.restock-request.index', compact('restockRequests'));
    }
}
