<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    // created_at & updated_at ada di database
    public $timestamps = true;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'price',
        'stock',
        'minimum_stock',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'product_suppliers', 'product_id', 'supplier_id')
                    ->withPivot('supplier_price');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'product_id', 'id');
    }
}
