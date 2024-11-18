<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id', 'name',  'product_id', 'unit_of_measurement', 'conversion_factor',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
