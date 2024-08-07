<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BqSection extends Model
{
    use HasFactory;

    protected $fillable = ['bq_document_id', 'section_name', 'details'];

    public function bqDocument()
    {
        return $this->belongsTo(BqDocument::class);
    }
}

