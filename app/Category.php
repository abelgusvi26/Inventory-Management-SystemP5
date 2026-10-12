<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'created_at',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
