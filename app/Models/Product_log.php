<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_log extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'name',
        'point',
        'rare',
        'image',
        'gacha_record_id',
        'user_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'user_id');
    }
}
