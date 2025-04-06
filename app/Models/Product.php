<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // You can define any properties or relationships for the Product model here
    protected $fillable = ['title', 'description', 'price', 'image'];
}

