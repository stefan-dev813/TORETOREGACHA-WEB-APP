<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gacha_record extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'gacha_id',
        'type',
        'status',
    ];
}
