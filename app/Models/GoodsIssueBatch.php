<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsIssueBatch extends Model
{
    use HasFactory;
    protected $fillable = [
        'goods_issue_detail_id',
        'warehouse_id',
        'batch_id',
        'quantity'
    ];

    public function goodsIssueDetail()
    {
        return $this->belongsTo(GoodsIssueDetail::class, 'goods_issue_detail_id');
    }
}
