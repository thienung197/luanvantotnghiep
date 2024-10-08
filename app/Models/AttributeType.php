<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class, 'attribute_type_id');
    }
}
