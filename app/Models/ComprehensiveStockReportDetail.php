<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprehensiveStockReportDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'comprehensive_stock_report_id',
        'product_id',
        'beginning_inventory',
        'stock_in',
        'stock_out',
        'ending_inventory'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function comprehensiveStockReport()
    {
        return $this->belongsTo(ComprehensiveStockReport::class, 'comprehensive_stock_report_id');
    }
}
