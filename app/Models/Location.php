<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'location_id';

    protected $fillable = ['location_name', 'status'];

    public function lostItems()
    {
        return $this->hasMany(LostItem::class, 'location_id', 'location_id');
    }
}
