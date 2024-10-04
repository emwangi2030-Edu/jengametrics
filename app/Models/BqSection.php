<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BqSection extends Model
{
    use HasFactory;

    protected $fillable = ['section_name', 'details', 'bq_document_id', 'project_id'];

    public function bqDocument()
    {
        return $this->belongsTo(BqDocument::class);
    }

    public function items()
    {
        return $this->hasMany(BqItem::class);
    }
}


