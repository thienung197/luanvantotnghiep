<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprehensiveStockReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'warehouse_id',
        'start_date',
        'end_date',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function getUserName()
    {
        return $this->user->name;
    }

    public function comprehensiveStockReportDetails()
    {
        return $this->hasMany(ComprehensiveStockReportDetail::class, 'comprehensive_stock_report_id');
    }
}
