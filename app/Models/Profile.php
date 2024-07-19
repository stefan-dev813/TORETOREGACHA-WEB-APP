<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'first_name_gana',
        'last_name_gana',
        'postal_code',
        'prefecture',
        'address',
        'phone',
        'status',
    ];
}
