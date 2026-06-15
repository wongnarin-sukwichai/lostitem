<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LostItem extends Model
{
    protected $primaryKey = 'lost_item_id';

    protected $fillable = [
        'item_name', 'category_id', 'location_id', 'student_id',
        'owner_first_name', 'owner_last_name', 'email', 'tel',
        'user_id', 'found_date', 'returned_date', 'status',
        'description', 'image', 'is_image_hidden', 'returned_timestamp',
    ];

    protected $casts = [
        'found_date'         => 'date',
        'returned_date'      => 'date',
        'returned_timestamp' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'location_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
