<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'element_id',
        'name',
        'description',
    ];

    public function element()
    {
        return $this->belongsTo(Element::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'sub_element_id');
    }
}
