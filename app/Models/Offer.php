<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{

    protected $fillable = [
        'title',
        'type',
        'start_date',
        'end_date',
        'rules',
        'quantity_or_amount',
    ];

}
