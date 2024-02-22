<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'sell_id',
        'user_id',
        'code',
        'is_approved',
    ];

    public function sell()
    {
        return $this->belongsTo(Sell::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
