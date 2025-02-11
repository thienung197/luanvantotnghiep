<?php

namespace App\Models;

use App\Traits\HandleUploadImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, HandleUploadImageTrait;
    protected $fillable = [
        'code',
        'name',
        'category_id',
        'description',
        'price', //So luong ton kho
        'unit_id',
        'status',
        'refrigerated',
        'provider_id'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function getUnitName()
    {
        return $this->unit ? $this->unit->name : 'No Unit';
    }

    public function productValues()
    {
        return $this->hasMany(ProductValue::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function goodsIssueDetails()
    {
        return $this->hasMany(GoodsIssueDetail::class);
    }

    public function goodsReceiptDetails()
    {
        return $this->hasMany(GoodsReceiptDetail::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class, 'product_id', 'id');
    }

    public function stockTakeDetails()
    {
        return $this->hasMany(StockTakeDetail::class);
    }

    public function restockRequestDetails()
    {
        return $this->hasMany(RestockRequestDetail::class, 'product_id');
    }


    public function getTotalAvailableQuantityAttribute()
    {
        return $this->batches->sum(function ($batch) {
            return $batch->inventories->sum('total_available_quantity');
        });
    }
}
