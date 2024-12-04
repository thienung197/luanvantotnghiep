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
