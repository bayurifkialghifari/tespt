<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'image',
        'description',
        'quantity'
    ];

    protected $casts = [
        'price' => 'integer',
        'quantity' => 'integer'
    ];
}
