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

    public function level()
    {
        return $this->belongsTo(BqLevel::class, 'bq_level_id');
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

    public function bomItems()
    {
        return $this->hasMany(BomItem::class, 'bq_section_id');
    }

    public function bomLabours()
    {
        return $this->hasMany(BomLabour::class, 'bq_section_id');
    }
}
