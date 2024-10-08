<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'attribute_type_id',
        'value'
    ];

    public function attributeType()
    {
        return $this->belongsTo(AttributeType::class, 'attribute_type_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
