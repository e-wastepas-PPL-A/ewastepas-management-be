<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Waste extends Model
{
    use HasFactory;
    protected $table = 'waste';
    protected $primaryKey = 'waste_id';
    protected $fillable = [
        'waste_name',
        'point',
        'waste_type_id',
        'image',
        'description',
    ];

    public function wasteType()
    {
        return $this->belongsTo(WasteType::class, 'waste_type_id');
    }
}

