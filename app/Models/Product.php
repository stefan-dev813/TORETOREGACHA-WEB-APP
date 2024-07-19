<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'point',
        'dp',
        'rare',
        // 'emission_percentage',
        'image',
        'marks',
        'lost_type',
        'is_last',
        'is_lost_product',  // 0-gacha product  1- gacha lost product 2-dp exchanger product
        'gacha_id',
        'product_type',
        'category_id',
        'status_product',
        'user_id',
        'gacha_record_id',
        'status',  // 1-gacha result  2- has exchanged to point 3-waiting  4- delivered
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
