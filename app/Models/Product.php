<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'description',
        'price',
        'stock',
        'low_stock_threshold'
    ];

    public function salesOrderItems()
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function isLowStock()
    {
        return $this->stock < $this->low_stock_threshold;
    }
}
