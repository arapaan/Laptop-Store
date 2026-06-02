<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image_url',
    ];

    public function carts(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class, 'cart_items')->withTimestamps()->withPivot('qty');
    }
}
