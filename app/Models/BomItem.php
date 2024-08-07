<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomItem extends Model
{
    use HasFactory;

    protected $fillable = ['bom_id', 'item_description', 'quantity', 'unit', 'rate', 'amount'];

    public function bom()
    {
        return $this->belongsTo(BOM::class);
    }
}
