<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'address',
        'product_name',
        'subscription_plan',
        'amount',
        'stripe_subscription_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
