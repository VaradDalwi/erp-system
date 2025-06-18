<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $fillable = ['user_id', 'order_date', 'total_amount'];

    public function items()
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
