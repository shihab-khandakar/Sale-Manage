<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable =[
        "name",
        "state",
        "location_url",
        "code",
        "upazila_id",
        "district_id",
        "division_id",
        "status",
    ];
}
