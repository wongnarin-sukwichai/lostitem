<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'category_id';

    protected $fillable = ['category_name', 'status'];

    public function lostItems()
    {
        return $this->hasMany(LostItem::class, 'category_id', 'category_id');
    }
}
