<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use App\Models\User;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'item_id',
        'postcode',
        'address',
        'building',
        'payment_method',
    ];
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
