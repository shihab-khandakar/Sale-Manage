<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;

class Union extends Model
{
    protected $guarded = [];

    public function upazilas()
    {
        return $this->belongsTo(Upazila::class);
    }
}
