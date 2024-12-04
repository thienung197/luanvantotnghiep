<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivingNotes extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchase_order_id',
        'provider_id',
        'user_id',
        'status'
    ];

    public function receivingDetails()
    {
        return $this->hasMany(ReceivingDetail::class, 'receiving_notes_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
}
