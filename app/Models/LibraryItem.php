<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'library_id',
        'section_id',
        'element_id',
        'item_id',
    ];

    public function library()
    {
        return $this->belongsTo(Library::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function element()
    {
        return $this->belongsTo(Element::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

