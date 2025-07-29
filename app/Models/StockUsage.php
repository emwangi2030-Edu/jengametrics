<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockUsage extends Model
{
    use HasFactory;
    protected $fillable = [
        'material_id',
        'quantity_used',
        'section_id',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
