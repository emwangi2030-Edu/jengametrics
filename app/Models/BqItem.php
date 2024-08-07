<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BqItem extends Model
{
    use HasFactory;

    protected $fillable = ['bq_document_id', 'bq_section_id', 'item_description', 'quantity', 'unit', 'rate', 'amount'];

    public function bqDocument()
    {
        return $this->belongsTo(BqDocument::class);
    }

    public function bqSection()
    {
        return $this->belongsTo(BqSection::class);
    }
}
