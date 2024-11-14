<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'user_id',
        'warehouse_id',
        'status',
        'restock_request_reason_id'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUserName()
    {
        return $this->user ? $this->user->name : '';
    }

    public function restockRequestDetails()
    {
        return $this->hasMany(RestockRequestDetail::class);
    }
}
