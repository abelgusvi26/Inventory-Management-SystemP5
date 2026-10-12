<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'created_at',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_suppliers', 'supplier_id', 'product_id')
                    ->withPivot('supplier_price');
    }
}
