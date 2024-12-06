<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'provider_id',
    ];

    // Khai báo thuộc tính tùy chỉnh
    protected $recordedProducts = [];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }

    public function setRecordedProducts($recordedProducts)
    {
        $this->recordedProducts = $recordedProducts;
    }

    public function getRecordedProducts()
    {
        return $this->recordedProducts;
    }



    public function receivingNotes()
    {
        return $this->hasMany(ReceivingNotes::class);
    }

    public function getTotalAmountAttribute()
    {
        return $this->purchaseOrderDetails->sum(function ($detail) {
            return ($detail->quantity * $detail->unit_price - $detail->discount);
        });
    }
}
