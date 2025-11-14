<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BqLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'bq_document_id',
        'project_id',
        'name',
        'description',
        'position',
    ];

    public function document()
    {
        return $this->belongsTo(BqDocument::class, 'bq_document_id');
    }

    public function sections()
    {
        return $this->hasMany(BqSection::class);
    }
}
