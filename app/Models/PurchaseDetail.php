<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'purchase_id',
        'good_id',
        'quantity',
        'price',
        'total',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function goods()
    {
        return $this->belongsTo(Good::class, 'good_id');
    }
}
