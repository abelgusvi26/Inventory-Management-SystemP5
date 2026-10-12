<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $table = 'stock_movements';

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'reference_number',
        'notes',
        'created_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
