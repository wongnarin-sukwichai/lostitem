<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    public $timestamps = false;
    protected $table = 'staffs';
    protected $primaryKey = 'staff_id';

    protected $fillable = ['first_name', 'last_name', 'position', 'status'];

    public function lostItems()
    {
        return $this->hasMany(LostItem::class, 'staff_id', 'staff_id');
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
