<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    
    protected $fillable = [
        "user_id",
        "f_name",
        "l_name",
        "state",
        "upazila_id",
        "district_id",
        "division_id",
        "nid",
        "birth_no",
        "birth",
    ];

}
