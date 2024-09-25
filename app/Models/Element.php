<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Element extends Model
{
    use HasFactory;

    protected $fillable = ['section_id', 'name', 'description'];

    // Relationship with Section
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subElements()
    {
        return $this->hasMany(SubElement::class);
    }

}

