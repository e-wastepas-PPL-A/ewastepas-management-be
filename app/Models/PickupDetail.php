<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupDetail extends Model
{
    protected $table = 'pickup_detail';
    public function waste()
    {
        return $this->belongsTo(Waste::class, 'waste_id');
    }

    public function pickup()
    {
        return $this->belongsTo(PickupWaste::class, 'pickup_id', 'pickup_id');
    }
}
