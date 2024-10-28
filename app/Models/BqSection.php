<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BqSection extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function bqDocument()
    {
        return $this->belongsTo(BqDocument::class);
    }

    public function items()
    {
        return $this->hasMany(BqItem::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}


