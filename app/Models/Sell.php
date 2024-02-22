<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'total_items',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function getTotalAttribute() {
        return $this->price * $this->quantity;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sellDetails()
    {
        return $this->hasMany(SellDetail::class);
    }

    public function sellApproval()
    {
        return $this->hasOne(SellApproval::class);
    }
}
