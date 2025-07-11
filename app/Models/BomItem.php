<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bom()
    {
        return $this->belongsTo(BOM::class);
    }

    public function item_material()
    {
        return $this->belongsTo(ItemMaterial::class, 'item_material_id');
    }

    public function requisitions()
    {
        return $this->hasMany(Requisition::class, 'bom_item_id');
    }
}
