<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'name',
        'email',
        'address',
        'product_name',
        'subscription_plan',
        'amount',
        'stripe_subscription_id',
    ];
}
