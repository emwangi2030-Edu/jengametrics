<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'unit_price', 
        'unit_of_measure', 
        'quantity_in_stock', 
        'supplier_id', 
        'contact_info', 
        'created_at'
    ];

    // A material belongs to one supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
