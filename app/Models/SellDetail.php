<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'sell_id',
        'good_id',
        'quantity',
        'price',
        'total',
    ];

    public function sell()
    {
        return $this->belongsTo(Sell::class);
    }

    public function goods()
    {
        return $this->belongsTo(Good::class, 'good_id');
    }
}
