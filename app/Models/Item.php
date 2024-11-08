<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function subElement()
    {
        return $this->belongsTo(SubElement::class);
    }

    public function itemMaterials()
    {
        return $this->hasMany(ItemMaterial::class, 'item_id');
    }

}
