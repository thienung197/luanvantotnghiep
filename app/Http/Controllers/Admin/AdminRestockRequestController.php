<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestockRequest;
use Illuminate\Http\Request;

class AdminRestockRequestController extends Controller
{
    protected $user;
    protected $restockRequest;
    public function __construct(RestockRequest $restockRequest)
    {
        $this->restockRequest = $restockRequest;
    }
    public function index()
    {
        $user = auth()->user();
        $restockRequests = $this->restockRequest::with('warehouse')->latest('id')->get();
        info($restockRequests);
        return view('admin.restock-request.index', compact('restockRequests'));
    }
}
