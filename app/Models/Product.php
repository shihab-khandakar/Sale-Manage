<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    
    protected $fillable = [
        "name",
        "brand_id",
        "category_id",
        "stock_in_price",
        "stock_out_price",
        "retailer_sale_price",
        "status",
        "offer_id",
        "code"
    ];


}
