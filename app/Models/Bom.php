<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bom extends Model
{
    use HasFactory;

    protected $fillable = ['bq_document_id', 'bom_name'];

    public function bqDocument()
    {
        return $this->belongsTo(BQDocument::class, 'bq_document_id');
    }

    public function items()
    {
        return $this->hasMany(BOMItem::class);
    }
}
