<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'items';
    protected $fillable = ['name', 'description', 'price', 'image'];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
