<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomLabour extends Model
{
    protected $guarded = [];

     public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
