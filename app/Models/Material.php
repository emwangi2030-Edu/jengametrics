<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $guarded = [];

    // A material belongs to one supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // A material belongs to a project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Add a computed property for total amount
    public function getTotalAmountAttribute()
    {
        return (float) $this->unit_price * (float) $this->quantity_in_stock;
    }

    // Corrected relationship (a material belongs to one product)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
