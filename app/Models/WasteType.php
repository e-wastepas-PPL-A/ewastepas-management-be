<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WasteType extends Model
{
    use HasFactory;
    protected $table = 'waste_type';

    protected $fillable = [
        'waste_type_name',
        'image',
    ];

    public function wastes()
    {
        return $this->hasMany(Waste::class, 'waste_type_id');
    }
}
