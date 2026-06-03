<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

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

    public function getImageAttribute()
    {
        if(!$this->attributes['image_url']) {
            return null;
        }

        if(filter_var($this->attributes['image_url'], FILTER_VALIDATE_URL)) {
            return $this->attributes['image_url'];
        }

        return config('app.url') . Storage::url($this->attributes['image_url']);
    }
}