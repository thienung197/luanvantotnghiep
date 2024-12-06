<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsIssueBatch extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'goods_issue_id',
        'warehouse_id',
        'batch_id',
        'quantity',
        'unit_price',
        'discount'
    ];


    public function goodsIssue()
    {
        return $this->belongsTo(GoodsIssue::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
