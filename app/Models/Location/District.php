<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $guarded = [];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function upazilas()
    {
        return $this->hasMany(Upazila::class);
    }
}
