<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class PickupWaste extends Model
{

    use HasFactory;
    protected $table = 'pickup_waste';
    protected $primaryKey = 'pickup_id';

    public function details()
    {
        return $this->hasMany(PickupDetail::class, 'pickup_detail_id', 'pickup_detail_id');
    }

    public function courier()
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    public function community()
    {
        return $this->belongsTo(User::class, 'community_id');
    }

    public function management()
    {
        return $this->belongsTo(User::class, 'management_id');
    }
}

